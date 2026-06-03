<?php
use App\Http\Controllers\UsuarioController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ComercioController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\AutoridadController;

Route::get('/', [App\Http\Controllers\InicioController::class, 'index']);

// Login / Registro
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::get('/register', [AuthController::class, 'showRegister']);
Route::post('/register', [AuthController::class, 'register']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Dashboard (Feed de Noticias)
Route::get('/dashboard', [App\Http\Controllers\DashboardController::class, 'index'])->middleware('auth')->name('dashboard');

// Perfil
Route::get('/perfil', [UsuarioController::class, 'perfil'])->middleware('auth');

// Reportar
Route::get('/reportar', [UsuarioController::class, 'formReportar'])->middleware('auth');
Route::post('/reportar', [UsuarioController::class, 'guardarReporte'])->middleware('auth');

// Mapa
Route::get('/mapa', [UsuarioController::class, 'mapa'])->middleware('auth');

//Recompensas y Canjes
Route::get('/recompensas', [UsuarioController::class, 'recompensas'])->middleware('auth');
Route::post('/recompensas/canjear/{id}', [UsuarioController::class, 'canjearRecompensa'])->middleware('auth');
Route::get('/mis-canjes', [UsuarioController::class, 'misCanjes'])->middleware('auth');

// Notificaciones
Route::get('/notificaciones', [UsuarioController::class, 'notificaciones'])->middleware('auth');
Route::post('/notificaciones/{id}/leer', [UsuarioController::class, 'marcarNotificacionLeida'])->middleware('auth');

// Comercio
Route::middleware(['auth', 'role:comercio'])->group(function () {
    Route::get('/comercio', [ComercioController::class, 'index']);
    Route::get('/comercio/ofertas', [ComercioController::class, 'ofertas']);
    Route::post('/comercio/ofertas', [ComercioController::class, 'storeOferta']);
    Route::delete('/comercio/ofertas/{id}', [ComercioController::class, 'deleteOferta']);
    Route::get('/comercio/estadisticas', [ComercioController::class, 'estadisticas']);
    Route::post('/comercio/validar-canje/{codigo}', [ComercioController::class, 'validarCanje']);
    Route::post('/comercio/validar-canje', [ComercioController::class, 'validarCanje']);
});



// Admin
Route::middleware(['auth', 'role:admin'])->group(function () {
    Route::get('/admin', [AdminController::class, 'index']);

    // Usuarios
    Route::get('/admin/usuarios', [AdminController::class, 'usuarios']);
    Route::get('/admin/usuarios/{id}/editar', [AdminController::class, 'editUsuario']);
    Route::put('/admin/usuarios/{id}', [AdminController::class, 'updateUsuario']);
    Route::delete('/admin/usuarios/{id}', [AdminController::class, 'deleteUsuario']);

    // Reportes
    Route::get('/admin/reportes', [AdminController::class, 'reportes']);
    Route::delete('/admin/reportes/{id}', [AdminController::class, 'deleteReporte']);

    // Comercios
    Route::get('/admin/comercios', [AdminController::class, 'comercios']);

    // Ofertas de Comercios
    Route::get('/admin/ofertas', [AdminController::class, 'ofertas']);
    Route::get('/admin/ofertas/{id}/edit', [AdminController::class, 'editOferta']);
    Route::put('/admin/ofertas/{id}', [AdminController::class, 'updateOferta']);
    Route::delete('/admin/ofertas/{id}', [AdminController::class, 'deleteOferta']);

    // Recompensas del Sistema
    Route::get('/admin/recompensas', [AdminController::class, 'recompensas']);
    Route::post('/admin/recompensas', [AdminController::class, 'storeRecompensa']);
    Route::delete('/admin/recompensas/{id}', [AdminController::class, 'deleteRecompensa']);
});

// Autoridad
Route::middleware(['auth', 'role:autoridad'])->group(function () {
    Route::get('/autoridad', [AutoridadController::class, 'index']);
    Route::get('/autoridad/reportes', [AutoridadController::class, 'reportes']);
    Route::get('/autoridad/validaciones', [AutoridadController::class, 'validaciones']);
    Route::get('/autoridad/validar_accion/{id}', [AutoridadController::class, 'validarAccion']);
    Route::post('/autoridad/validar_accion/{id}', [AutoridadController::class, 'guardarValidacion']);
});
