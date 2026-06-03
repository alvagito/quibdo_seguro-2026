<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class VerifyToken
{
    /**
     * Verificar que el request tenga un token Sanctum válido.
     * Usado para proteger rutas internas del servicio.
     */
    public function handle(Request $request, Closure $next, string ...$roles): Response
    {
        if (!$request->user()) {
            return response()->json([
                'success' => false,
                'message' => 'No autenticado. Token inválido o expirado.',
                'data'    => null,
            ], 401);
        }

        // Verificar rol si se especificó
        if (!empty($roles) && !in_array($request->user()->rol, $roles)) {
            return response()->json([
                'success' => false,
                'message' => 'No tienes permiso para realizar esta acción.',
                'data'    => null,
            ], 403);
        }

        return $next($request);
    }
}
