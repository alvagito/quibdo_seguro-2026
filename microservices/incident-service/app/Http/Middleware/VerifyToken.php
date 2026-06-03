<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Symfony\Component\HttpFoundation\Response;

/**
 * Valida el token Bearer contra el auth-service.
 * Inyecta los datos del usuario en el request.
 */
class VerifyToken
{
    public function handle(Request $request, Closure $next, string ...$roles): Response
    {
        $token = $request->bearerToken();

        if (!$token) {
            return response()->json([
                'success' => false,
                'message' => 'Token no proporcionado.',
                'data'    => null,
            ], 401);
        }

        try {
            $authUrl = rtrim(config('services.auth_service.url'), '/');
            $response = Http::withToken($token)
                ->timeout(5)
                ->get("{$authUrl}/api/auth/profile");

            if (!$response->successful()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Token inválido o expirado.',
                    'data'    => null,
                ], 401);
            }

            $userData = $response->json('data');

            // Verificar rol si se especificó
            if (!empty($roles) && !in_array($userData['rol'], $roles)) {
                return response()->json([
                    'success' => false,
                    'message' => 'No tienes permiso para realizar esta acción.',
                    'data'    => null,
                ], 403);
            }

            // Inyectar datos del usuario en el request
            $request->merge(['_auth_user' => $userData]);
            $request->attributes->set('auth_user', $userData);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al verificar autenticación.',
                'data'    => null,
            ], 503);
        }

        return $next($request);
    }
}
