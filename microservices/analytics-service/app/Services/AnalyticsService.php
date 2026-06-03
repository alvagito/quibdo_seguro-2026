<?php

namespace App\Services;

use Carbon\Carbon;
use MongoDB\Laravel\Eloquent\Model;

// Modelos inline para analytics (solo lectura, misma BD)
class IncidenteAnalytics extends Model {
    protected $connection = 'mongodb';
    protected $collection = 'incidentes';
    public $timestamps = true;
}
class UsuarioAnalytics extends Model {
    protected $connection = 'mongodb';
    protected $collection = 'usuarios';
    public $timestamps = true;
}
class CanjeAnalytics extends Model {
    protected $connection = 'mongodb';
    protected $collection = 'canjes';
    public $timestamps = true;
}

class AnalyticsService
{
    public function dashboard(): array
    {
        return [
            'total_reportes'    => IncidenteAnalytics::count(),
            'reportes_hoy'      => IncidenteAnalytics::whereDate('created_at', Carbon::today())->count(),
            'reportes_semana'   => IncidenteAnalytics::where('created_at', '>=', Carbon::now()->subDays(7))->count(),
            'usuarios_activos'  => UsuarioAnalytics::where('updated_at', '>=', Carbon::now()->subDays(30))->count(),
            'puntos_entregados' => (int) UsuarioAnalytics::sum('puntos'),
            'canjes_realizados' => CanjeAnalytics::count(),
        ];
    }

    public function incidentes(array $filtros = []): array
    {
        $query = IncidenteAnalytics::query();

        if (!empty($filtros['desde'])) {
            $query->where('created_at', '>=', $filtros['desde']);
        }

        $porTipo = [
            'robo'      => IncidenteAnalytics::where('id_tipo_incidente', 1)->count(),
            'accidente' => IncidenteAnalytics::where('id_tipo_incidente', 2)->count(),
            'violencia' => IncidenteAnalytics::where('id_tipo_incidente', 3)->count(),
            'otro'      => IncidenteAnalytics::where('id_tipo_incidente', 4)->count(),
        ];

        $recientes = IncidenteAnalytics::orderBy('created_at', 'desc')
            ->limit(20)
            ->get()
            ->map(fn($i) => [
                'id'                   => (string) $i->_id,
                'id_tipo_incidente'    => $i->id_tipo_incidente,
                'descripcion'          => $i->descripcion ?? '',
                'direccion_aproximada' => $i->direccion_aproximada ?? '',
                'latitud'              => $i->latitud ?? null,
                'longitud'             => $i->longitud ?? null,
                'created_at'           => $i->created_at,
            ])->toArray();

        return [
            'por_tipo'  => $porTipo,
            'recientes' => $recientes,
            'total'     => array_sum($porTipo),
        ];
    }

    public function usuarios(): array
    {
        return [
            'total'         => UsuarioAnalytics::count(),
            'por_rol'       => [
                'normal'    => UsuarioAnalytics::where('rol', 'normal')->count(),
                'admin'     => UsuarioAnalytics::where('rol', 'admin')->count(),
                'autoridad' => UsuarioAnalytics::where('rol', 'autoridad')->count(),
                'comercio'  => UsuarioAnalytics::where('rol', 'comercio')->count(),
            ],
            'activos_30d'   => UsuarioAnalytics::where('updated_at', '>=', Carbon::now()->subDays(30))->count(),
            'nuevos_semana' => UsuarioAnalytics::where('created_at', '>=', Carbon::now()->subDays(7))->count(),
        ];
    }
}
