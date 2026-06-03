<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Incidente;
use Carbon\Carbon;
use App\Models\Recompensa;


class UsuarioController extends Controller
{
    protected $notificacionService;

    public function __construct(\App\Services\NotificacionService $notificacionService)
    {
        $notificacionService = new \App\Services\NotificacionService();
        $this->notificacionService = $notificacionService;
    }

    /**
     * Mostrar perfil
     */
    public function perfil()
    {
        return view('perfil');
    }

    /**
     * Mostrar formulario de reporte
     */
    public function formReportar()
    {
        return view('reportar');
    }

    /**
 * Mostrar incidentes en mapa con filtros
 */
public function mapa(Request $request)
{
    $query = \App\Models\Incidente::query();

    // Filtro por tipo
    if ($request->has('tipo') && $request->tipo != '') {
        $query->where('id_tipo_incidente', (int)$request->tipo);
    }



    // Filtro por periodo
    if ($request->has('periodo') && $request->periodo != '') {
        switch ($request->periodo) {
            case '24h':
                $query->where('created_at', '>=', Carbon::now()->subHours(24));
                break;
            case '7d':
                $query->where('created_at', '>=', Carbon::now()->subDays(7));
                break;
            case '30d':
                $query->where('created_at', '>=', Carbon::now()->subDays(30));
                break;
        }
    }

    $incidentes = $query->get();
    
    return view('mapa', compact('incidentes'));
}

/**
 * Mostrar recompensas
 */
public function recompensas()
{
    $recompensas = Recompensa::all();
    return view('recompensas', compact('recompensas'));
}

/**
 * Canjear recompensa (ahora con ofertas de comercios)
 */
public function canjearRecompensa($id)
{
    $user = Auth::user();
    
    // Intentar buscar primero como oferta
    $oferta = \App\Models\Oferta::find($id);
    
    if ($oferta) {
        // Es una oferta de comercio
        if ($user->puntos >= $oferta->puntos) {
            // Generar código QR único
            $codigoQR = strtoupper(substr(md5(uniqid()), 0, 8));

            // Crear registro de canje
            \App\Models\Canje::create([
                'id_usuario' => $user->_id,
                'id_oferta' => $oferta->_id,
                'id_comercio' => $oferta->id_comercio,
                'puntos_canjeados' => $oferta->puntos,
                'codigo_qr' => $codigoQR,
                'estado' => 'pendiente',
                'fecha_canje' => now()
            ]);

            // Restar puntos
            $user->puntos -= $oferta->puntos;
            $user->save();

            // Notificar
            $this->notificacionService->notificarCanje($user, $oferta, $codigoQR);

            return redirect('/recompensas')->with('success', "Has canjeado: {$oferta->titulo}. Código: {$codigoQR}");
        }
        
        return redirect('/recompensas')->with('error', 'No tienes suficientes puntos.');
    }
    
    // Si no es oferta, buscar como recompensa del sistema
    $recompensa = \App\Models\Recompensa::findOrFail($id);
    
    if ($user->puntos >= $recompensa->puntos) {
        // Restar puntos
        $user->puntos -= $recompensa->puntos;
        $user->save();

        // Crear notificación
        \App\Models\Notificacion::create([
            'id_usuario' => $user->_id,
            'tipo' => 'canje',
            'mensaje' => "Has canjeado la recompensa: {$recompensa->nombre}",
            'leida' => false
        ]);

        return redirect('/recompensas')->with('success', "Has canjeado: {$recompensa->nombre}");
    }

    return redirect('/recompensas')->with('error', 'No tienes suficientes puntos.');
}

/**
 * Ver historial de canjes
 */
public function misCanjes()
{
    $canjes = \App\Models\Canje::where('id_usuario', Auth::id())
        ->with(['oferta', 'comercio'])
        ->orderBy('created_at', 'desc')
        ->get();
    
    return view('mis_canjes', compact('canjes'));
}

/**
 * Ver notificaciones
 */
public function notificaciones()
{
    $notificaciones = Auth::user()->notificaciones()
        ->orderBy('created_at', 'desc')
        ->get();
    
    return view('notificaciones', compact('notificaciones'));
}

/**
 * Marcar notificación como leída
 */
public function marcarNotificacionLeida($id)
{
    $notificacion = \App\Models\Notificacion::findOrFail($id);
    
    if ($notificacion->id_usuario == Auth::id()) {
        $notificacion->leida = true;
        $notificacion->save();
    }
    
    return redirect()->back();
}


    /**
     * Guardar reporte en BD
     */
    public function guardarReporte(Request $request)
    {
        $request->validate([
            'id_tipo_incidente' => 'required|integer',
            'descripcion' => 'required|string|max:500',
            'direccion_aproximada' => 'required|string|max:255',
            'latitud' => 'required|numeric',
            'longitud' => 'required|numeric',
            'evidencia_foto' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048'
        ]);

        // Verificar reportes duplicados en la misma ubicación (radio de 100m)
        $reportesCercanos = Incidente::where('id_estado', 1)
            ->whereBetween('latitud', [$request->latitud - 0.001, $request->latitud + 0.001])
            ->whereBetween('longitud', [$request->longitud - 0.001, $request->longitud + 0.001])
            ->where('id_tipo_incidente', $request->id_tipo_incidente)
            ->where('created_at', '>=', Carbon::now()->subHours(24))
            ->first();

        if ($reportesCercanos) {
            return redirect('/reportar')->with('warning', 'Ya existe un reporte similar en esta ubicación. ¿Deseas confirmarlo en lugar de crear uno nuevo?');
        }

        $fotoPath = null;
        if ($request->hasFile('evidencia_foto')) {
            $fotoPath = $request->file('evidencia_foto')->store('evidencias', 'public');
        }

        $incidente = Incidente::create([
            'id_usuario' => Auth::id(),
            'id_tipo_incidente' => $request->id_tipo_incidente,
            'latitud' => $request->latitud,
            'longitud' => $request->longitud,
            'descripcion' => $request->descripcion,
            'fecha_hora_incidente' => Carbon::now(),
            'fecha_hora_reporte' => Carbon::now(),
            'evidencia_foto_url' => $fotoPath,
            'direccion_aproximada' => $request->direccion_aproximada
        ]);

        // Notificar a autoridades
        $this->notificacionService->notificarNuevoReporte($incidente);

        return redirect('/reportar')->with('success', 'Reporte enviado correctamente.');
    }
}
