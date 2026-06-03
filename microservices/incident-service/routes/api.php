<?php

use App\Http\Controllers\IncidentController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Incident Service - API Routes
|--------------------------------------------------------------------------
| Puerto: 8002
*/

Route::get('/health', fn() => response()->json([
    'success' => true,
    'service' => 'incident-service',
    'status' => 'ok',
]));

// Rutas públicas (lectura del mapa)
Route::get('/incidentes',      [IncidentController::class, 'index']);
Route::get('/incidentes/{id}', [IncidentController::class, 'show']);

// Rutas protegidas (requieren token válido)
Route::middleware('verify.token')->group(function () {
    Route::post('/incidentes',              [IncidentController::class, 'store']);
    Route::post('/incidentes/comentarios',  [IncidentController::class, 'comentar']);
});
