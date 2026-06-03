<?php

namespace App\Http\Controllers;

use App\Models\Incidente;
use App\Services\NotificacionService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AutoridadController extends Controller
{
    public function index()
    {
        return view('autoridad.index');
    }

    public function reportes()
    {
        $reportes = Incidente::with('usuario')
            ->orderBy('created_at', 'desc')
            ->get();

        return view('autoridad.reportes', compact('reportes'));
    }

    public function validaciones()
    {
        $reportes = Incidente::with('usuario')
            ->where('id_estado', 1)
            ->orderBy('created_at', 'asc')
            ->get();

        return view('autoridad.validaciones', compact('reportes'));
    }

    public function validarAccion(string $id)
    {
        $reporte = Incidente::with('usuario')->findOrFail($id);

        return view('autoridad.validar_accion', compact('reporte'));
    }

    public function guardarValidacion(Request $request, string $id)
    {
        $data = $request->validate([
            'estado' => 'required|integer|in:1,2,3',
            'comentarios' => 'nullable|string|max:1000',
        ]);

        $reporte = Incidente::findOrFail($id);
        $estadoAnterior = (int) ($reporte->id_estado ?? 1);

        $reporte->id_estado = $data['estado'];
        $reporte->comentarios_autoridad = $data['comentarios'] ?? null;
        $reporte->validado_por = Auth::id();
        $reporte->fecha_validacion = now();
        $reporte->save();

        if ($estadoAnterior !== $data['estado'] && in_array($data['estado'], [2, 3], true)) {
            if ($data['estado'] === 2 && $reporte->usuario) {
                $reporte->usuario->puntos = (int) ($reporte->usuario->puntos ?? 0) + 10;
                $reporte->usuario->save();
            }

            app(NotificacionService::class)->notificarValidacionReporte($reporte, $data['estado']);
        }

        return redirect('/autoridad/validaciones')->with('success', 'Validación guardada correctamente.');
    }
}
