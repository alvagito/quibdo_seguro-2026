<?php

use App\Http\Controllers\AuthController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Auth Service - API Routes
|--------------------------------------------------------------------------
| Puerto: 8001
*/

Route::get('/health', fn() => response()->json([
    'success' => true,
    'service' => 'auth-service',
    'status' => 'ok',
]));

Route::prefix('auth')->group(function () {

    // Rutas públicas
    Route::post('/login',    [AuthController::class, 'login']);
    Route::post('/register', [AuthController::class, 'register']);

    // Rutas protegidas: autenticación resuelta en el controlador
    Route::post('/logout',  [AuthController::class, 'logout']);
    Route::get('/profile',  [AuthController::class, 'profile']);

    // Endpoint interno (llamado por otros microservicios)
    // En producción proteger con IP whitelist o token de servicio
    Route::get('/user/{id}', [AuthController::class, 'getUser']);
    Route::get('/users-by-role/{rol}', [AuthController::class, 'usersByRole']);
    Route::patch('/user/{id}/puntos', [AuthController::class, 'updatePuntos']);
});
