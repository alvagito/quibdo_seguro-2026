<?php

namespace App\Http\Controllers;

use App\Services\ProxyService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

/**
 * Gateway Controller
 * Recibe todas las peticiones del frontend y las redirige al microservicio correcto.
 */
class GatewayController extends Controller
{
    public function __construct(private ProxyService $proxy) {}

    // ---------------------------------------------------------------
    // AUTH SERVICE
    // ---------------------------------------------------------------

    public function authLogin(Request $request): JsonResponse
    {
        return $this->proxy->forward('auth_service', '/api/auth/login', $request);
    }

    public function authRegister(Request $request): JsonResponse
    {
        return $this->proxy->forward('auth_service', '/api/auth/register', $request);
    }

    public function authLogout(Request $request): JsonResponse
    {
        return $this->proxy->forward('auth_service', '/api/auth/logout', $request);
    }

    public function authProfile(Request $request): JsonResponse
    {
        return $this->proxy->forward('auth_service', '/api/auth/profile', $request);
    }

    // ---------------------------------------------------------------
    // INCIDENT SERVICE
    // ---------------------------------------------------------------

    public function incidentesIndex(Request $request): JsonResponse
    {
        return $this->proxy->forward('incident_service', '/api/incidentes', $request);
    }

    public function incidentesShow(Request $request, string $id): JsonResponse
    {
        return $this->proxy->forward('incident_service', "/api/incidentes/{$id}", $request);
    }

    public function incidentesStore(Request $request): JsonResponse
    {
        return $this->proxy->forward('incident_service', '/api/incidentes', $request);
    }

    public function incidentesComentarios(Request $request): JsonResponse
    {
        return $this->proxy->forward('incident_service', '/api/incidentes/comentarios', $request);
    }

    // ---------------------------------------------------------------
    // REWARDS SERVICE
    // ---------------------------------------------------------------

    public function recompensasIndex(Request $request): JsonResponse
    {
        return $this->proxy->forward('rewards_service', '/api/recompensas', $request);
    }

    public function canjear(Request $request): JsonResponse
    {
        return $this->proxy->forward('rewards_service', '/api/canjear', $request);
    }

    public function misCanjes(Request $request): JsonResponse
    {
        return $this->proxy->forward('rewards_service', '/api/canjes', $request);
    }

    public function validarCanje(Request $request): JsonResponse
    {
        return $this->proxy->forward('rewards_service', '/api/validar-canje', $request);
    }

    public function estadisticasComercio(Request $request): JsonResponse
    {
        return $this->proxy->forward('rewards_service', '/api/estadisticas/comercio', $request);
    }

    // ---------------------------------------------------------------
    // NOTIFICATION SERVICE
    // ---------------------------------------------------------------

    public function notificaciones(Request $request): JsonResponse
    {
        return $this->proxy->forward('notification_service', '/api/notificaciones', $request);
    }

    public function marcarNotificacionLeida(Request $request, string $id): JsonResponse
    {
        return $this->proxy->forward('notification_service', "/api/notificaciones/{$id}", $request);
    }

    // ---------------------------------------------------------------
    // ANALYTICS SERVICE
    // ---------------------------------------------------------------

    public function statsDashboard(Request $request): JsonResponse
    {
        return $this->proxy->forward('analytics_service', '/api/stats/dashboard', $request);
    }

    public function statsIncidentes(Request $request): JsonResponse
    {
        return $this->proxy->forward('analytics_service', '/api/stats/incidentes', $request);
    }

    public function statsUsuarios(Request $request): JsonResponse
    {
        return $this->proxy->forward('analytics_service', '/api/stats/usuarios', $request);
    }
}
