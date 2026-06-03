<?php

namespace App\Listeners;

use App\Events\CanjeRealizadoEvent;
use App\Events\ReporteValidadoEvent;
use App\Services\NotificacionService;
use Illuminate\Support\Facades\Log;

class NotificarUsuarioListener
{
    public function __construct(private NotificacionService $notificacionService) {}

    public function handleCanje(CanjeRealizadoEvent $event): void
    {
        try {
            $this->notificacionService->crear(
                $event->idUsuario,
                'canje',
                '🎁 Canje Exitoso',
                "Has canjeado: {$event->tituloOferta}. Código: {$event->codigoQR}",
                '/mis-canjes',
                ['codigo_qr' => $event->codigoQR]
            );
        } catch (\Exception $e) {
            Log::error('NotificarUsuarioListener::handleCanje error: ' . $e->getMessage());
        }
    }

    public function handleValidacion(ReporteValidadoEvent $event): void
    {
        try {
            $esValidado = $event->estado === 'validado';
            $titulo     = $esValidado ? '✅ Reporte Validado' : '❌ Reporte Rechazado';
            $mensaje    = "Tu reporte ha sido {$event->estado} por las autoridades.";

            if ($esValidado) {
                $mensaje .= ' Has ganado 10 puntos.';
            }

            $this->notificacionService->crear(
                $event->idUsuario,
                'validacion_reporte',
                $titulo,
                $mensaje,
                '/perfil',
                ['estado' => $event->estado]
            );
        } catch (\Exception $e) {
            Log::error('NotificarUsuarioListener::handleValidacion error: ' . $e->getMessage());
        }
    }
}
