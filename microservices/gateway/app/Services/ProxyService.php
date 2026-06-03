<?php

namespace App\Services;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

/**
 * Servicio de proxy HTTP para reenviar peticiones a los microservicios.
 */
class ProxyService
{
    /**
     * Reenviar una petición al microservicio destino.
     *
     * @param string  $serviceKey  Clave del servicio en config/services.php
     * @param string  $path        Path del endpoint en el microservicio
     * @param Request $request     Request original del cliente
     */
    public function forward(string $serviceKey, string $path, Request $request): \Illuminate\Http\JsonResponse
    {
        $baseUrl = rtrim(config("services.{$serviceKey}.url"), '/');
        $url     = $baseUrl . '/' . ltrim($path, '/');

        try {
            $http = Http::timeout(10)
                ->withHeaders($this->forwardHeaders($request));

            $method   = strtolower($request->method());
            $response = match ($method) {
                'get'    => $http->get($url, $request->query()),
                'post'   => $request->hasFile('evidencia_foto')
                                ? $http->attach(
                                    'evidencia_foto',
                                    $request->file('evidencia_foto')->get(),
                                    $request->file('evidencia_foto')->getClientOriginalName()
                                  )->post($url, $request->except('evidencia_foto'))
                                : $http->post($url, $request->all()),
                'put'    => $http->put($url, $request->all()),
                'patch'  => $http->patch($url, $request->all()),
                'delete' => $http->delete($url),
                default  => $http->get($url),
            };

            return response()->json(
                $response->json(),
                $response->status()
            );

        } catch (\Illuminate\Http\Client\ConnectionException $e) {
            Log::error("Gateway: servicio {$serviceKey} no disponible. {$e->getMessage()}");

            return response()->json([
                'success' => false,
                'message' => "El servicio no está disponible en este momento.",
                'data'    => null,
            ], 503);
        } catch (\Exception $e) {
            Log::error("Gateway: error al reenviar a {$serviceKey}. {$e->getMessage()}");

            return response()->json([
                'success' => false,
                'message' => 'Error interno del gateway.',
                'data'    => null,
            ], 500);
        }
    }

    /**
     * Construir headers a reenviar al microservicio.
     */
    private function forwardHeaders(Request $request): array
    {
        $headers = [
            'Accept'       => 'application/json',
            'Content-Type' => 'application/json',
        ];

        // Reenviar token de autorización si existe
        if ($request->hasHeader('Authorization')) {
            $headers['Authorization'] = $request->header('Authorization');
        }

        return $headers;
    }
}
