<?php

namespace App\Http\Controllers;

use App\Models\Incidente;
use App\Models\User;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class AnalyticsController extends Controller
{
    /**
     * Obtener KPIs principales para el dashboard.
     */
    public function dashboardStats()
    {
        return response()->json([
            'success' => true,
            'data' => [
                'total_incidentes' => Incidente::count(),
                'incidentes_24h' => Incidente::where('created_at', '>=', now()->subHours(24))->count(),
                'tasa_validacion' => $this->getValidacionRate(),
                'usuarios_activos' => User::where('updated_at', '>=', now()->subDays(7))->count(),
            ]
        ]);
    }

    /**
     * Estadísticas detalladas de incidentes por tipo y tiempo.
     */
    public function incidentStats()
    {
        // Agrupación por tipo de incidente usando agregación de MongoDB
        $porTipo = Incidente::raw(function($collection) {
            return $collection->aggregate([
                ['$group' => ['_id' => '$id_tipo_incidente', 'count' => ['$sum' => 1]]],
                ['$sort' => ['count' => -1]]
            ]);
        });

        // Tendencia de los últimos 7 días
        $tendencia = [];
        for ($i = 6; $i >= 0; $i--) {
            $fecha = Carbon::now()->subDays($i)->format('Y-m-d');
            $tendencia[$fecha] = Incidente::whereDate('created_at', $fecha)->count();
        }

        return response()->json([
            'success' => true,
            'data' => [
                'distribucion_tipo' => $porTipo,
                'tendencia_semanal' => $tendencia
            ]
        ]);
    }

    /**
     * Análisis de puntos y actividad de usuarios.
     */
    public function userStats()
    {
        $topUsuarios = User::orderBy('puntos', 'desc')->limit(10)->get(['nombre', 'puntos']);
        
        return response()->json([
            'success' => true,
            'data' => [
                'ranking_ciudadano' => $topUsuarios,
                'total_puntos_circulacion' => User::sum('puntos')
            ]
        ]);
    }

    /**
     * Calcula el porcentaje de reportes que han sido validados por autoridades.
     */
    private function getValidacionRate()
    {
        $total = Incidente::count();
        if ($total === 0) return 0;

        // Asumiendo que id_estado 2 y 3 son estados validados/procesados
        $validados = Incidente::whereIn('id_estado', [2, 3])->count();

        return round(($validados / $total) * 100, 2);
    }
}