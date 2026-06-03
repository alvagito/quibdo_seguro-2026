<?php

namespace App\Http\Controllers;

use App\Services\RewardService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class RewardController extends Controller
{
    public function __construct(private RewardService $rewardService) {}

    /**
     * GET /api/recompensas
     * Pública - lista recompensas y ofertas disponibles.
     */
    public function index(): JsonResponse
    {
        $data = $this->rewardService->listarRecompensas();

        return response()->json([
            'success' => true,
            'message' => 'Recompensas obtenidas.',
            'data'    => $data,
        ]);
    }

    /**
     * POST /api/canjear
     * Requiere: auth
     */
    public function canjear(Request $request): JsonResponse
    {
        $request->validate([
            'id_oferta' => 'required|string',
        ]);

        $authUser = $request->attributes->get('auth_user');

        $result = $this->rewardService->canjear($authUser['id'], $request->id_oferta);

        $status = $result['success'] ? 201 : 400;

        return response()->json($result, $status);
    }

    /**
     * GET /api/canjes
     * Requiere: auth
     */
    public function misCanjes(Request $request): JsonResponse
    {
        $authUser = $request->attributes->get('auth_user');

        $canjes = $this->rewardService->canjesDeUsuario($authUser['id']);

        return response()->json([
            'success' => true,
            'message' => 'Canjes obtenidos.',
            'data'    => $canjes,
            'total'   => count($canjes),
        ]);
    }

    /**
     * POST /api/validar-canje
     * Requiere: auth, rol=comercio
     */
    public function validarCanje(Request $request): JsonResponse
    {
        $request->validate([
            'codigo_qr' => 'required|string',
        ]);

        $authUser = $request->attributes->get('auth_user');

        $result = $this->rewardService->validarCanje($request->codigo_qr, $authUser['id']);

        $status = $result['success'] ? 200 : 404;

        return response()->json($result, $status);
    }

    /**
     * GET /api/estadisticas/comercio
     * Requiere: auth, rol=comercio
     */
    public function estadisticasComercio(Request $request): JsonResponse
    {
        $authUser = $request->attributes->get('auth_user');

        $stats = $this->rewardService->estadisticasComercio($authUser['id']);

        return response()->json([
            'success' => true,
            'message' => 'Estadísticas obtenidas.',
            'data'    => $stats,
        ]);
    }
}
