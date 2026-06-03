<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Incidente;
use App\Models\User;
use Throwable;

class InicioController extends Controller
{
    /**
     * Mostrar página de inicio con mapa e incidentes públicos
     */
    public function index()
    {
        try {
            // Obtener los últimos 20 incidentes para el mapa
            $incidentes = Incidente::with('usuario')
                ->orderBy('created_at', 'desc')
                ->limit(20)
                ->get();

            // Obtener los últimos 5 reportes para mostrar en la lista
            $ultimosReportes = Incidente::with('usuario')
                ->orderBy('created_at', 'desc')
                ->limit(5)
                ->get();

            // Estadísticas generales para mostrar en la página
            $estadisticas = [
                'total_reportes' => Incidente::count(),
                'reportes_publicados' => Incidente::count(),
                'usuarios_activos' => User::where('updated_at', '>=', now()->subDays(30))->count(),
            ];
        } catch (Throwable $exception) {
            $incidentes = collect();
            $ultimosReportes = collect();
            $estadisticas = [
                'total_reportes' => 0,
                'reportes_publicados' => 0,
                'usuarios_activos' => 0,
            ];
        }

        return view('inicio', compact('incidentes', 'ultimosReportes', 'estadisticas'));
    }
}
