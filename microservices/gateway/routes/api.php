<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Http;
use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Gateway Routes
|--------------------------------------------------------------------------
| Este gateway actúa como punto de entrada único para todos los microservicios.
*/

Route::get('/health', fn() => response()->json([
    'success' => true,
    'service' => 'gateway',
    'status' => 'ok',
]));

Route::get('/health/services', function () {
    $services = [
        'auth-service' => env('AUTH_SERVICE_URL') . '/api/health',
        'incident-service' => env('INCIDENT_SERVICE_URL') . '/api/health',
        'rewards-service' => env('REWARDS_SERVICE_URL') . '/api/health',
        'notification-service' => env('NOTIFICATION_SERVICE_URL') . '/api/health',
        'analytics-service' => env('ANALYTICS_SERVICE_URL') . '/api/health',
    ];

    $statuses = [];

    foreach ($services as $service => $url) {
        try {
            $response = Http::timeout(3)->get($url);
            $statuses[$service] = [
                'status' => $response->successful() ? 'ok' : 'error',
                'http_status' => $response->status(),
            ];
        } catch (Throwable $exception) {
            $statuses[$service] = [
                'status' => 'offline',
                'http_status' => null,
            ];
        }
    }

    $allOk = collect($statuses)->every(fn(array $status) => $status['status'] === 'ok');

    return response()->json([
        'success' => $allOk,
        'service' => 'gateway',
        'status' => $allOk ? 'ok' : 'degraded',
        'services' => $statuses,
    ], $allOk ? 200 : 503);
});

$proxyJson = function (Request $request, string $method, string $url, bool $withToken = false) {
    $client = Http::timeout(10);

    if ($withToken && $request->bearerToken()) {
        $client = $client->withToken($request->bearerToken());
    }

    $response = $client->{$method}($url, $request->all());

    return response($response->body(), $response->status())
        ->header('Content-Type', $response->header('Content-Type', 'application/json'));
};

$proxyMultipart = function (Request $request, string $url, bool $withToken = false) {
    $client = Http::timeout(10)->asMultipart();

    if ($withToken && $request->bearerToken()) {
        $client = $client->withToken($request->bearerToken());
    }

    foreach ($request->except('evidencia_foto') as $name => $value) {
        if (is_array($value)) {
            foreach ($value as $item) {
                $client = $client->attach($name . '[]', (string) $item);
            }
        } else {
            $client = $client->attach($name, (string) $value);
        }
    }

    if ($request->hasFile('evidencia_foto')) {
        $file = $request->file('evidencia_foto');
        $client = $client->attach(
            'evidencia_foto',
            file_get_contents($file->getRealPath()),
            $file->getClientOriginalName()
        );
    }

    $response = $client->post($url, []);

    return response($response->body(), $response->status())
        ->header('Content-Type', $response->header('Content-Type', 'application/json'));
};

Route::get('/', fn() => response()->json([
    'success' => true,
    'message' => 'Quibdo Seguro API Gateway',
    'data' => [
        'web_client' => 'http://localhost:8006',
        'health' => 'http://localhost:8000/api/health',
    ],
]));

Route::middleware('rate.limit:120')->group(function () use ($proxyJson, $proxyMultipart) {
    Route::prefix('auth')->group(function () use ($proxyJson) {
        Route::post('/login', fn(Request $request) => $proxyJson($request, 'post', env('AUTH_SERVICE_URL') . '/api/auth/login'));
        Route::post('/register', fn(Request $request) => $proxyJson($request, 'post', env('AUTH_SERVICE_URL') . '/api/auth/register'));
        Route::post('/logout', fn(Request $request) => $proxyJson($request, 'post', env('AUTH_SERVICE_URL') . '/api/auth/logout', true));
        Route::get('/profile', fn(Request $request) => $proxyJson($request, 'get', env('AUTH_SERVICE_URL') . '/api/auth/profile', true));
    });

    Route::prefix('incidentes')->group(function () use ($proxyJson, $proxyMultipart) {
        Route::get('/', fn(Request $request) => $proxyJson($request, 'get', env('INCIDENT_SERVICE_URL') . '/api/incidentes'));
        Route::get('/{id}', fn(Request $request, $id) => $proxyJson($request, 'get', env('INCIDENT_SERVICE_URL') . '/api/incidentes/' . $id));
        Route::post('/', fn(Request $request) => $proxyMultipart($request, env('INCIDENT_SERVICE_URL') . '/api/incidentes', true));
        Route::post('/comentarios', fn(Request $request) => $proxyJson($request, 'post', env('INCIDENT_SERVICE_URL') . '/api/incidentes/comentarios', true));
    });

    Route::prefix('recompensas')->group(function () use ($proxyJson) {
        Route::get('/', fn(Request $request) => $proxyJson($request, 'get', env('REWARDS_SERVICE_URL') . '/api/recompensas'));
    });

    Route::post('/canjear', fn(Request $request) => $proxyJson($request, 'post', env('REWARDS_SERVICE_URL') . '/api/canjear', true));
    Route::get('/canjes', fn(Request $request) => $proxyJson($request, 'get', env('REWARDS_SERVICE_URL') . '/api/canjes', true));
    Route::post('/validar-canje', fn(Request $request) => $proxyJson($request, 'post', env('REWARDS_SERVICE_URL') . '/api/validar-canje', true));
    Route::get('/estadisticas/comercio', fn(Request $request) => $proxyJson($request, 'get', env('REWARDS_SERVICE_URL') . '/api/estadisticas/comercio', true));

    Route::prefix('notificaciones')->group(function () use ($proxyJson) {
        Route::get('/', fn(Request $request) => $proxyJson($request, 'get', env('NOTIFICATION_SERVICE_URL') . '/api/notificaciones', true));
        Route::put('/{id}', fn(Request $request, $id) => $proxyJson($request, 'put', env('NOTIFICATION_SERVICE_URL') . '/api/notificaciones/' . $id, true));
    });

    Route::prefix('stats')->group(function () use ($proxyJson) {
        Route::get('/dashboard', fn(Request $request) => $proxyJson($request, 'get', env('ANALYTICS_SERVICE_URL') . '/api/stats/dashboard'));
        Route::get('/incidentes', fn(Request $request) => $proxyJson($request, 'get', env('ANALYTICS_SERVICE_URL') . '/api/stats/incidentes'));
        Route::get('/usuarios', fn(Request $request) => $proxyJson($request, 'get', env('ANALYTICS_SERVICE_URL') . '/api/stats/usuarios', true));
    });
});
