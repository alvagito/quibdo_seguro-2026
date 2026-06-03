<?php

use App\Http\Controllers\RewardController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Rewards Service - API Routes
|--------------------------------------------------------------------------
| Puerto: 8003
*/

Route::get('/health', fn() => response()->json([
    'success' => true,
    'service' => 'rewards-service',
    'status' => 'ok',
]));

// Pública
Route::get('/recompensas', [RewardController::class, 'index']);

// Requieren autenticación
Route::middleware('verify.token')->group(function () {
    Route::post('/canjear',  [RewardController::class, 'canjear']);
    Route::get('/canjes',    [RewardController::class, 'misCanjes']);
});

// Solo comercios
Route::middleware(['verify.token:comercio'])->group(function () {
    Route::post('/validar-canje',          [RewardController::class, 'validarCanje']);
    Route::get('/estadisticas/comercio',   [RewardController::class, 'estadisticasComercio']);
});
