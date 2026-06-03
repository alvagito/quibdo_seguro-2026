<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Cache\RateLimiter;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RateLimitMiddleware
{
    public function __construct(private RateLimiter $limiter) {}

    public function handle(Request $request, Closure $next, int $maxAttempts = 60): Response
    {
        $key = 'gateway:' . ($request->ip() ?? 'unknown');

        if ($this->limiter->tooManyAttempts($key, $maxAttempts)) {
            $seconds = $this->limiter->availableIn($key);

            return response()->json([
                'success' => false,
                'message' => "Demasiadas peticiones. Intenta de nuevo en {$seconds} segundos.",
                'data'    => null,
            ], 429);
        }

        $this->limiter->hit($key, 60); // ventana de 60 segundos

        return $next($request);
    }
}
