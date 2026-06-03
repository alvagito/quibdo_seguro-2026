<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AnalyticsController;

Route::prefix('stats')->group(function () {
    // Dashboard general
    Route::get('/dashboard', [AnalyticsController::class, 'dashboardStats']);
    // Detalles de incidentes (Heatmaps/Tipos)
    Route::get('/incidentes', [AnalyticsController::class, 'incidentStats']);
    // Métricas de usuarios y gamificación
    Route::get('/usuarios', [AnalyticsController::class, 'userStats']);
});