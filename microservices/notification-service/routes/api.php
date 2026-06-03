<?php

use App\Http\Controllers\NotificacionController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Notification Service - API Routes
|--------------------------------------------------------------------------
| Puerto: 8004
*/

Route::get('/health', fn() => response()->json([
    'success' => true,
    'service' => 'notification-service',
    'status' => 'ok',
]));

// Rutas protegidas para usuarios
Route::middleware('verify.token')->group(function () {
    Route::get('/notificaciones',          [NotificacionController::class, 'index']);
    Route::put('/notificaciones/{id}',     [NotificacionController::class, 'marcarLeida']);
});

// Endpoints internos (llamados por otros microservicios)
// En producción: proteger con IP whitelist o service token
Route::prefix('notificaciones')->group(function () {
    Route::post('/nuevo-reporte',       [NotificacionController::class, 'nuevoReporte']);
    Route::post('/canje',               [NotificacionController::class, 'canje']);
    Route::post('/validacion-reporte',  [NotificacionController::class, 'validacionReporte']);
});
