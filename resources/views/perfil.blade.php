<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Mi Perfil - Quibdó Seguro</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="{{ asset('assets/css/styles.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/theme.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/perfil.css') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body>
@include('components.header')

<main class="perfil-main">
    <div class="perfil-hero">
        <div class="perfil-hero-content">
            <div class="perfil-avatar-large">
                <i class="fas fa-user-circle"></i>
            </div>
            <h1>{{ Auth::user()->nombre }}</h1>
            <p class="perfil-rol-badge badge-{{ Auth::user()->rol }}">
                <i class="fas fa-shield-alt"></i> {{ ucfirst(Auth::user()->rol) }}
            </p>
        </div>
    </div>

    <div class="perfil-container">
        <div class="perfil-grid">
            <!-- Tarjeta de Información Personal -->
            <div class="perfil-card">
                <div class="card-header">
                    <h3><i class="fas fa-user"></i> Información Personal</h3>
                </div>
                <div class="card-body">
                    <div class="info-row">
                        <div class="info-label">
                            <i class="fas fa-signature"></i> Nombre Completo
                        </div>
                        <div class="info-value">{{ Auth::user()->nombre }}</div>
                    </div>
                    <div class="info-row">
                        <div class="info-label">
                            <i class="fas fa-envelope"></i> Correo Electrónico
                        </div>
                        <div class="info-value">{{ Auth::user()->email }}</div>
                    </div>
                    <div class="info-row">
                        <div class="info-label">
                            <i class="fas fa-calendar-alt"></i> Miembro Desde
                        </div>
                        <div class="info-value">{{ Auth::user()->created_at->format('d/m/Y') }}</div>
                    </div>
                </div>
            </div>

            <!-- Tarjeta de Puntos y Recompensas -->
            <div class="perfil-card puntos-card">
                <div class="card-header">
                    <h3><i class="fas fa-star"></i> Puntos y Recompensas</h3>
                </div>
                <div class="card-body">
                    <div class="puntos-display">
                        <div class="puntos-icon">
                            <i class="fas fa-trophy"></i>
                        </div>
                        <div class="puntos-info">
                            <span class="puntos-numero">{{ Auth::user()->puntos ?? 0 }}</span>
                            <span class="puntos-texto">Puntos Acumulados</span>
                        </div>
                    </div>
                    <div class="puntos-progress">
                        <div class="progress-bar">
                            <div class="progress-fill" style="width: {{ min((Auth::user()->puntos ?? 0) / 100 * 100, 100) }}%"></div>
                        </div>
                        <p class="progress-text">
                            @if((Auth::user()->puntos ?? 0) < 100)
                                Te faltan {{ 100 - (Auth::user()->puntos ?? 0) }} puntos para tu próxima recompensa
                            @else
                                ¡Tienes suficientes puntos para canjear recompensas!
                            @endif
                        </p>
                    </div>
                    <a href="{{ url('/recompensas') }}" class="btn-primary-perfil">
                        <i class="fas fa-gift"></i> Ver Recompensas
                    </a>
                </div>
            </div>

            <!-- Tarjeta de Estadísticas -->
            <div class="perfil-card">
                <div class="card-header">
                    <h3><i class="fas fa-chart-line"></i> Mis Estadísticas</h3>
                </div>
                <div class="card-body">
                    @php
                        $misReportes = Auth::user()->incidentes()->count();
                        $misCanjes = Auth::user()->canjes()->count();
                    @endphp
                    <div class="stats-grid-perfil">
                        <div class="stat-item-perfil">
                            <div class="stat-icon-perfil">
                                <i class="fas fa-exclamation-triangle"></i>
                            </div>
                            <div class="stat-data">
                                <span class="stat-number-perfil">{{ $misReportes }}</span>
                                <span class="stat-label-perfil">Reportes</span>
                            </div>
                        </div>
                        <div class="stat-item-perfil">
                            <div class="stat-icon-perfil">
                                <i class="fas fa-eye"></i>
                            </div>
                            <div class="stat-data">
                                <span class="stat-number-perfil">{{ $misReportes }}</span>
                                <span class="stat-label-perfil">Publicados</span>
                            </div>
                        </div>
                        <div class="stat-item-perfil">
                            <div class="stat-icon-perfil">
                                <i class="fas fa-ticket-alt"></i>
                            </div>
                            <div class="stat-data">
                                <span class="stat-number-perfil">{{ $misCanjes }}</span>
                                <span class="stat-label-perfil">Canjes</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Tarjeta de Acciones Rápidas -->
            <div class="perfil-card">
                <div class="card-header">
                    <h3><i class="fas fa-bolt"></i> Acciones Rápidas</h3>
                </div>
                <div class="card-body">
                    <div class="quick-actions">
                        <a href="{{ url('/reportar') }}" class="action-btn">
                            <i class="fas fa-plus-circle"></i>
                            <span>Nuevo Reporte</span>
                        </a>
                        <a href="{{ url('/mapa') }}" class="action-btn">
                            <i class="fas fa-map-marked-alt"></i>
                            <span>Ver Mapa</span>
                        </a>
                        <a href="{{ url('/mis-canjes') }}" class="action-btn">
                            <i class="fas fa-history"></i>
                            <span>Mis Canjes</span>
                        </a>
                        <a href="{{ url('/notificaciones') }}" class="action-btn">
                            <i class="fas fa-bell"></i>
                            <span>Notificaciones</span>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</main>

<footer>
    <p>© {{ date('Y') }} Quibdó Seguro - Todos los derechos reservados.</p>
</footer>
</body>
</html>
