<?php

namespace App\Http\Controllers;

use App\Http\Requests\LoginRequest;
use App\Http\Requests\RegisterRequest;
use App\Services\AuthService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class AuthController extends Controller
{
    public function __construct(private AuthService $authService) {}

    /**
     * POST /api/auth/login
     */
    public function login(LoginRequest $request): JsonResponse
    {
        $result = $this->authService->login(
            $request->email,
            $request->password
        );

        $status = $result['success'] ? 200 : 401;

        return response()->json($result, $status);
    }

    /**
     * POST /api/auth/register
     */
    public function register(RegisterRequest $request): JsonResponse
    {
        $result = $this->authService->register($request->validated());

        return response()->json($result, 201);
    }

    /**
     * POST /api/auth/logout
     * Requiere: auth:sanctum
     */
    public function logout(Request $request): JsonResponse
    {
        $user = $request->user() ?? $this->authService->userFromRequest($request);

        $result = $this->authService->logout($user);

        return response()->json($result);
    }

    /**
     * GET /api/auth/profile
     * Requiere: auth:sanctum
     */
    public function profile(Request $request): JsonResponse
    {
        $user = $request->user() ?? $this->authService->userFromRequest($request);

        $result = $this->authService->profile($user);

        return response()->json($result);
    }

    /**
     * GET /api/auth/user/{id}
     * Endpoint interno para que otros servicios validen usuarios.
     */
    public function getUser(string $id): JsonResponse
    {
        $user = $this->authService->getUserById($id);

        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'Usuario no encontrado.',
                'data'    => null,
            ], 404);
        }

        return response()->json([
            'success' => true,
            'message' => 'Usuario encontrado.',
            'data'    => $user,
        ]);
    }

    public function usersByRole(string $rol): JsonResponse
    {
        $users = $this->authService->getUsersByRole($rol);

        return response()->json([
            'success' => true,
            'message' => "Usuarios con rol {$rol} obtenidos.",
            'data'    => $users,
        ]);
    }

    public function updatePuntos(string $id, Request $request): JsonResponse
    {
        $request->validate([
            'puntos' => 'required|integer|min:0',
        ]);

        $updated = $this->authService->updateUserPoints($id, $request->input('puntos'));

        if (!$updated) {
            return response()->json([
                'success' => false,
                'message' => 'Usuario no encontrado o no se pudo actualizar los puntos.',
                'data'    => null,
            ], 404);
        }

        return response()->json([
            'success' => true,
            'message' => 'Puntos actualizados correctamente.',
            'data'    => null,
        ]);
    }
}
