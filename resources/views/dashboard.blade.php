<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - Quibdó Seguro</title>
    <link rel="stylesheet" href="{{ asset('assets/css/styles.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/dashboard.css') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body class="dashboard-body">
    @include('components.header')

    <main class="dashboard-main">
        <!-- Hero Section con Bienvenida -->
        <section class="dashboard-hero">
            <div class="hero-overlay"></div>
            <div class="hero-content-dash">
                <div class="welcome-message">
                    <h1>¡Bienvenido, {{ $usuario->nombre }}! 👋</h1>
                    <p>Mantente informado sobre lo que sucede en tu comunidad</p>
                </div>
                <div class="quick-stats">
                    <div class="stat-card">
                        <div class="stat-icon">
                            <i class="fas fa-exclamation-circle"></i>
                        </div>
                        <div class="stat-info">
                            <span class="stat-number">{{ $stats['reportes_hoy'] }}</span>
                            <span class="stat-label">Reportes Hoy</span>
                        </div>
                    </div>
                    <div class="stat-card">
                        <div class="stat-icon">
                            <i class="fas fa-calendar-week"></i>
                        </div>
                        <div class="stat-info">
                            <span class="stat-number">{{ $stats['reportes_semana'] }}</span>
                            <span class="stat-label">Esta Semana</span>
                        </div>
                    </div>
                    <div class="stat-card">
                        <div class="stat-icon">
                            <i class="fas fa-star"></i>
                        </div>
                        <div class="stat-info">
                            <span class="stat-number">{{ $usuario->puntos }}</span>
                            <span class="stat-label">Tus Puntos</span>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <div class="dashboard-container">
            <!-- Sidebar con Estadísticas -->
            <aside class="dashboard-sidebar">
                <div class="sidebar-card">
                    <h3><i class="fas fa-chart-pie"></i> Estadísticas</h3>
                    <div class="stats-list">
                        <div class="stat-item">
                            <span class="stat-label-side">Total Reportes</span>
                            <span class="stat-value-side">{{ $stats['total_reportes'] }}</span>
                        </div>
                        <div class="stat-item">
                            <span class="stat-label-side">Usuarios Activos</span>
                            <span class="stat-value-side">{{ $stats['usuarios_activos'] }}</span>
                        </div>
                    </div>
                </div>

                <div class="sidebar-card">
                    <h3><i class="fas fa-list"></i> Por Tipo</h3>
                    <div class="tipo-list">
                        <div class="tipo-item tipo-robo">
                            <i class="fas fa-user-secret"></i>
                            <span>Robos</span>
                        </div>
                        <div class="tipo-item tipo-accidente">
                            <i class="fas fa-car-crash"></i>
                            <span>Accidentes</span>
                        </div>
                        <div class="tipo-item tipo-violencia">
                            <i class="fas fa-fist-raised"></i>
                            <span>Violencia</span>
                        </div>
                        <div class="tipo-item tipo-otro">
                            <i class="fas fa-ellipsis-h"></i>
                            <span>Otros</span>
                        </div>
                    </div>
                </div>

                <div class="sidebar-card cta-card">
                    <i class="fas fa-bullhorn"></i>
                    <h3>¿Viste algo?</h3>
                    <p>Reporta incidentes y ayuda a tu comunidad</p>
                    <a href="{{ url('/reportar') }}" class="btn btn-primary-dash">
                        <i class="fas fa-plus-circle"></i> Reportar Ahora
                    </a>
                </div>
            </aside>

            <!-- Feed de Noticias -->
            <section class="dashboard-feed">
                <div class="feed-header">
                    <h2><i class="fas fa-newspaper"></i> Últimos Reportes</h2>
                    <div class="feed-filters">
                        <button class="filter-btn active" data-filter="all">
                            <i class="fas fa-th"></i> Todos
                        </button>
                        <button class="filter-btn" data-filter="hoy">
                            <i class="fas fa-clock"></i> Hoy
                        </button>
                        <button class="filter-btn" data-filter="semana">
                            <i class="fas fa-calendar-week"></i> Semana
                        </button>
                    </div>
                </div>

                <div class="feed-content">
                    @forelse($incidentes as $incidente)
                        <article class="feed-card" 
                                 data-tipo="{{ $incidente->id_tipo_incidente }}"
                                 data-date="{{ $incidente->created_at->timestamp }}">
                            <div class="feed-card-header">
                                <div class="user-info">
                                    <div class="user-avatar">
                                        <i class="fas fa-user-circle"></i>
                                    </div>
                                    <div class="user-details">
                                        <span class="user-name">{{ $incidente->usuario->nombre ?? 'Usuario' }}</span>
                                        <span class="post-time">
                                            <i class="fas fa-clock"></i> {{ $incidente->created_at->diffForHumans() }}
                                        </span>
                                    </div>
                                </div>
                                <div class="incident-badge badge-tipo-{{ $incidente->id_tipo_incidente }}">
                                    @switch($incidente->id_tipo_incidente)
                                        @case(1)
                                            <i class="fas fa-user-secret"></i> Robo
                                            @break
                                        @case(2)
                                            <i class="fas fa-car-crash"></i> Accidente
                                            @break
                                        @case(3)
                                            <i class="fas fa-fist-raised"></i> Violencia
                                            @break
                                        @default
                                            <i class="fas fa-exclamation-triangle"></i> Otro
                                    @endswitch
                                </div>
                            </div>

                            <div class="feed-card-body">
                                <p class="incident-description">{{ $incidente->descripcion }}</p>
                                
                                @if($incidente->evidencia_foto_url)
                                    <div class="incident-image">
                                        <img src="{{ asset('storage/' . $incidente->evidencia_foto_url) }}" alt="Evidencia">
                                    </div>
                                @endif

                                <div class="incident-location">
                                    <i class="fas fa-map-marker-alt"></i>
                                    <span>{{ $incidente->direccion_aproximada }}</span>
                                </div>
                            </div>

                            <div class="feed-card-footer">
                                <div class="incident-status">
                                    <i class="fas fa-eye"></i> Publicado
                                </div>
                                <a href="{{ url('/mapa') }}" class="btn-view-map">
                                    <i class="fas fa-map"></i> Ver en Mapa
                                </a>
                            </div>
                        </article>
                    @empty
                        <div class="empty-state">
                            <i class="fas fa-inbox"></i>
                            <h3>No hay reportes aún</h3>
                            <p>Sé el primero en reportar un incidente en tu comunidad</p>
                            <a href="{{ url('/reportar') }}" class="btn btn-primary-dash">
                                <i class="fas fa-plus-circle"></i> Crear Reporte
                            </a>
                        </div>
                    @endforelse
                </div>
            </section>
        </div>
    </main>

    <footer>
        <p>© {{ date('Y') }} Quibdó Seguro - Todos los derechos reservados.</p>
    </footer>

    <script src="{{ asset('assets/js/dashboard.js') }}"></script>
</body>
</html>
