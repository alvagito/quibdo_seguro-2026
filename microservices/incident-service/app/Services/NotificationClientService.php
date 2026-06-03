<?php

namespace App\Services;

use App\Interfaces\NotificationInterface;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

/**
 * Implementación HTTP del NotificationInterface.
 * Llama al notification-service vía REST.
 */
class NotificationClientService implements NotificationInterface
{
    private string $baseUrl;

    public function __construct()
    {
        $this->baseUrl = rtrim(config('services.notification_service.url'), '/');
    }

    public function notificarNuevoReporte(array $incidente): bool
    {
        try {
            $response = Http::timeout(5)->post("{$this->baseUrl}/api/notificaciones/nuevo-reporte", [
                'incidente' => $incidente,
            ]);

            return $response->successful();
        } catch (\Exception $e) {
            Log::error('NotificationClientService::notificarNuevoReporte error: ' . $e->getMessage());
            return false;
        }
    }

    public function notificarValidacionReporte(string $idUsuario, string $estado): bool
    {
        try {
            $response = Http::timeout(5)->post("{$this->baseUrl}/api/notificaciones/validacion-reporte", [
                'id_usuario' => $idUsuario,
                'estado'     => $estado,
            ]);

            return $response->successful();
        } catch (\Exception $e) {
            Log::error('NotificationClientService::notificarValidacionReporte error: ' . $e->getMessage());
            return false;
        }
    }
}
