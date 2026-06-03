<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Incidente;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    /**
     * Dashboard principal con feed de noticias
     */
    public function index()
    {
        $usuario = Auth::user();
        
        // Obtener incidentes recientes (últimos 20)
        $incidentes = Incidente::with('usuario')
            ->orderBy('created_at', 'desc')
            ->limit(20)
            ->get();
        
        // Estadísticas generales
        $stats = [
            'total_reportes' => Incidente::count(),
            'reportes_hoy' => Incidente::whereDate('created_at', today())->count(),
            'reportes_semana' => Incidente::where('created_at', '>=', now()->subDays(7))->count(),
            'usuarios_activos' => User::where('updated_at', '>=', now()->subDays(30))->count(),
        ];
        
        // Incidentes por tipo - Asegurar que siempre retorne un número
        $incidentesPorTipo = [
            'robo' => Incidente::where('id_tipo_incidente', 1)->count() ?? 0,
            'accidente' => Incidente::where('id_tipo_incidente', 2)->count() ?? 0,
            'violencia' => Incidente::where('id_tipo_incidente', 3)->count() ?? 0,
            'otro' => Incidente::where('id_tipo_incidente', 4)->count() ?? 0,
        ];
        
        return view('dashboard', compact('usuario', 'incidentes', 'stats', 'incidentesPorTipo'));
    }
}
