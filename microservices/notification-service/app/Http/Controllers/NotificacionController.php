<?php

namespace App\Http\Controllers;

use App\Events\CanjeRealizadoEvent;
use App\Events\NuevoReporteEvent;
use App\Events\ReporteValidadoEvent;
use App\Services\NotificacionService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class NotificacionController extends Controller
{
    public function __construct(private NotificacionService $notificacionService) {}

    /**
     * GET /api/notificaciones
     * Requiere: auth (id_usuario viene del token validado)
     */
    public function index(Request $request): JsonResponse
    {
        $authUser       = $request->attributes->get('auth_user');
        $notificaciones = $this->notificacionService->obtenerDeUsuario($authUser['id']);

        return response()->json([
            'success'    => true,
            'message'    => 'Notificaciones obtenidas.',
            'data'       => $notificaciones,
            'no_leidas'  => $this->notificacionService->contarNoLeidas($authUser['id']),
        ]);
    }

    /**
     * PUT /api/notificaciones/{id}
     * Requiere: auth
     */
    public function marcarLeida(Request $request, string $id): JsonResponse
    {
        $authUser = $request->attributes->get('auth_user');
        $result   = $this->notificacionService->marcarLeida($id, $authUser['id']);

        $status = $result['success'] ? 200 : 404;

        return response()->json($result, $status);
    }

    // ---------------------------------------------------------------
    // Endpoints internos (llamados por otros microservicios)
    // ---------------------------------------------------------------

    /**
     * POST /api/notificaciones/nuevo-reporte
     * Llamado por incident-service al crear un incidente.
     */
    public function nuevoReporte(Request $request): JsonResponse
    {
        $request->validate([
            'incidente' => 'required|array',
        ]);

        // Disparar evento (preparado para colas/RabbitMQ)
        NuevoReporteEvent::dispatch($request->incidente);

        return response()->json([
            'success' => true,
            'message' => 'Evento de nuevo reporte procesado.',
            'data'    => null,
        ]);
    }

    /**
     * POST /api/notificaciones/canje
     * Llamado por rewards-service al realizar un canje.
     */
    public function canje(Request $request): JsonResponse
    {
        $request->validate([
            'id_usuario'    => 'required|string',
            'titulo_oferta' => 'required|string',
            'codigo_qr'     => 'required|string',
        ]);

        CanjeRealizadoEvent::dispatch(
            $request->id_usuario,
            $request->titulo_oferta,
            $request->codigo_qr
        );

        return response()->json([
            'success' => true,
            'message' => 'Evento de canje procesado.',
            'data'    => null,
        ]);
    }

    /**
     * POST /api/notificaciones/validacion-reporte
     * Llamado por incident-service al validar un reporte.
     */
    public function validacionReporte(Request $request): JsonResponse
    {
        $request->validate([
            'id_usuario' => 'required|string',
            'estado'     => 'required|in:validado,rechazado',
        ]);

        ReporteValidadoEvent::dispatch($request->id_usuario, $request->estado);

        return response()->json([
            'success' => true,
            'message' => 'Evento de validación procesado.',
            'data'    => null,
        ]);
    }
}
