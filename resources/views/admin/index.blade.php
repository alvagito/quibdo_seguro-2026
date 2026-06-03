<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Panel de Administración - Quibdó Seguro</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="{{ asset('assets/css/styles.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/pages.css') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body>
<header>
    <div class="logo">
        <h1><a href="{{ url('/admin') }}">Quibdó Seguro - Admin</a></h1>
    </div>
    <nav>
        <ul>
            <li><a href="{{ url('/admin') }}"><i class="fas fa-home"></i> Dashboard</a></li>
            <li><a href="{{ url('/admin/usuarios') }}"><i class="fas fa-users"></i> Usuarios</a></li>
            <li><a href="{{ url('/admin/reportes') }}"><i class="fas fa-exclamation-triangle"></i> Reportes</a></li>
            <li><a href="{{ url('/admin/comercios') }}"><i class="fas fa-store"></i> Comercios</a></li>
            <li><a href="{{ url('/admin/recompensas') }}"><i class="fas fa-gift"></i> Recompensas</a></li>
            <li>
                <form action="{{ url('/logout') }}" method="POST" style="display:inline;">
                    @csrf
                    <button type="submit" class="btn btn-header">
                        <i class="fas fa-sign-out-alt"></i> Cerrar Sesión
                    </button>
                </form>
            </li>
        </ul>
    </nav>
</header>

<main style="background: #f5f7fa; min-height: 100vh; padding: 2rem 0;">
    <!-- Hero Section -->
    <div class="page-hero">
        <h1><i class="fas fa-tachometer-alt"></i> Panel de Administración</h1>
        <p>Gestiona toda la plataforma desde aquí</p>
    </div>

    <div class="modern-container">
        <!-- Estadísticas Rápidas -->
        <div class="modern-grid">
            <div class="modern-card" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white;">
                <div style="display: flex; align-items: center; gap: 1.5rem;">
                    <div style="width: 70px; height: 70px; background: rgba(255,255,255,0.2); border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: 2rem;">
                        <i class="fas fa-users"></i>
                    </div>
                    <div>
                        <div style="font-size: 2.5rem; font-weight: 700;">{{ \App\Models\User::count() }}</div>
                        <div style="opacity: 0.9;">Usuarios Totales</div>
                    </div>
                </div>
            </div>

            <div class="modern-card" style="background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%); color: white;">
                <div style="display: flex; align-items: center; gap: 1.5rem;">
                    <div style="width: 70px; height: 70px; background: rgba(255,255,255,0.2); border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: 2rem;">
                        <i class="fas fa-exclamation-triangle"></i>
                    </div>
                    <div>
                        <div style="font-size: 2.5rem; font-weight: 700;">{{ \App\Models\Incidente::count() }}</div>
                        <div style="opacity: 0.9;">Reportes Totales</div>
                    </div>
                </div>
            </div>

            <div class="modern-card" style="background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%); color: white;">
                <div style="display: flex; align-items: center; gap: 1.5rem;">
                    <div style="width: 70px; height: 70px; background: rgba(255,255,255,0.2); border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: 2rem;">
                        <i class="fas fa-store"></i>
                    </div>
                    <div>
                        <div style="font-size: 2.5rem; font-weight: 700;">{{ \App\Models\User::where('rol', 'comercio')->count() }}</div>
                        <div style="opacity: 0.9;">Comercios Aliados</div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Acciones Rápidas -->
        <div class="modern-card" style="margin-top: 2rem;">
            <div class="modern-card-header">
                <h2><i class="fas fa-bolt"></i> Acciones Rápidas</h2>
            </div>
            <div class="modern-grid">
                <a href="{{ url('/admin/usuarios') }}" class="modern-card" style="text-decoration: none; color: inherit; transition: transform 0.3s;">
                    <div style="text-align: center; padding: 1rem;">
                        <div style="width: 80px; height: 80px; margin: 0 auto 1rem; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: 2.5rem; color: white;">
                            <i class="fas fa-users-cog"></i>
                        </div>
                        <h3 style="margin-bottom: 0.5rem;">Gestionar Usuarios</h3>
                        <p style="color: #718096; font-size: 0.9rem;">Ver, editar y eliminar usuarios</p>
                    </div>
                </a>

                <a href="{{ url('/admin/reportes') }}" class="modern-card" style="text-decoration: none; color: inherit; transition: transform 0.3s;">
                    <div style="text-align: center; padding: 1rem;">
                        <div style="width: 80px; height: 80px; margin: 0 auto 1rem; background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%); border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: 2.5rem; color: white;">
                            <i class="fas fa-clipboard-list"></i>
                        </div>
                        <h3 style="margin-bottom: 0.5rem;">Gestionar Reportes</h3>
                        <p style="color: #718096; font-size: 0.9rem;">Revisar y eliminar reportes</p>
                    </div>
                </a>

                <a href="{{ url('/admin/comercios') }}" class="modern-card" style="text-decoration: none; color: inherit; transition: transform 0.3s;">
                    <div style="text-align: center; padding: 1rem;">
                        <div style="width: 80px; height: 80px; margin: 0 auto 1rem; background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%); border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: 2.5rem; color: white;">
                            <i class="fas fa-store-alt"></i>
                        </div>
                        <h3 style="margin-bottom: 0.5rem;">Gestionar Comercios</h3>
                        <p style="color: #718096; font-size: 0.9rem;">Ver comercios y sus ofertas</p>
                    </div>
                </a>

                <a href="{{ url('/admin/recompensas') }}" class="modern-card" style="text-decoration: none; color: inherit; transition: transform 0.3s;">
                    <div style="text-align: center; padding: 1rem;">
                        <div style="width: 80px; height: 80px; margin: 0 auto 1rem; background: linear-gradient(135deg, #fa709a 0%, #fee140 100%); border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: 2.5rem; color: white;">
                            <i class="fas fa-gift"></i>
                        </div>
                        <h3 style="margin-bottom: 0.5rem;">Gestionar Recompensas</h3>
                        <p style="color: #718096; font-size: 0.9rem;">Crear y eliminar recompensas</p>
                    </div>
                </a>
            </div>
        </div>

        <!-- Actividad Reciente -->
        <div class="modern-card" style="margin-top: 2rem;">
            <div class="modern-card-header">
                <h2><i class="fas fa-history"></i> Actividad Reciente</h2>
            </div>
            <div style="padding: 1.5rem;">
                @php
                    $reportesRecientes = \App\Models\Incidente::with('usuario')->orderBy('created_at', 'desc')->limit(5)->get();
                @endphp
                @forelse($reportesRecientes as $reporte)
                    <div style="padding: 1rem; border-bottom: 1px solid #e2e8f0; display: flex; justify-content: space-between; align-items: center;">
                        <div>
                            <strong>{{ $reporte->usuario->nombre ?? 'Usuario' }}</strong> reportó un incidente
                            <div style="color: #718096; font-size: 0.85rem; margin-top: 0.25rem;">
                                <i class="fas fa-clock"></i> {{ $reporte->created_at->diffForHumans() }}
                            </div>
                        </div>
                        <span class="badge-modern badge-info">Nuevo</span>
                    </div>
                @empty
                    <p style="text-align: center; color: #718096; padding: 2rem;">No hay actividad reciente</p>
                @endforelse
            </div>
        </div>
    </div>
</main>

<footer>
    <p>© {{ date('Y') }} Quibdó Seguro - Panel de Administración</p>
</footer>

<style>
.modern-card:hover {
    transform: translateY(-5px);
}
</style>
</body>
</html>
