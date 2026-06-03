<?php

namespace App\Listeners;

use App\Events\NuevoReporteEvent;
use App\Services\NotificacionService;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class NotificarAutoridadesListener
{
    public function __construct(private NotificacionService $notificacionService) {}

    public function handle(NuevoReporteEvent $event): void
    {
        try {
            // Obtener todas las autoridades desde auth-service
            $authUrl   = rtrim(config('services.auth_service.url'), '/');
            $response  = Http::timeout(5)->get("{$authUrl}/api/auth/users-by-role/autoridad");

            if (!$response->successful()) {
                Log::warning('NotificarAutoridadesListener: no se pudo obtener autoridades.');
                return;
            }

            $autoridades = $response->json('data', []);
            $incidente   = $event->incidente;

            foreach ($autoridades as $autoridad) {
                $this->notificacionService->crear(
                    $autoridad['id'],
                    'nuevo_reporte',
                    '🚨 Nuevo Reporte',
                    "Se ha reportado un incidente en {$incidente['direccion_aproximada']}",
                    "/autoridad/validar_accion/{$incidente['id']}",
                    ['id_incidente' => $incidente['id']]
                );
            }
        } catch (\Exception $e) {
            Log::error('NotificarAutoridadesListener error: ' . $e->getMessage());
        }
    }
}
