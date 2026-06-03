<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Gestión de Comercios - Admin</title>
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
                    <button type="submit" class="btn btn-header"><i class="fas fa-sign-out-alt"></i> Cerrar Sesión</button>
                </form>
            </li>
        </ul>
    </nav>
</header>

<main style="background: #f5f7fa; min-height: 100vh; padding: 2rem 0;">
    <div class="page-hero">
        <h1><i class="fas fa-store-alt"></i> Gestión de Comercios</h1>
        <p>Administra los comercios aliados y sus ofertas</p>
    </div>

    <div class="modern-container">
        <div class="modern-card">
            <div class="modern-card-header">
                <h2><i class="fas fa-list"></i> Comercios Aliados ({{ $comercios->count() }})</h2>
            </div>

            <div class="modern-grid">
                @forelse($comercios as $c)
                    <div class="modern-card" style="border-left: 4px solid #28a745;">
                        <div style="display: flex; align-items: center; gap: 1rem; margin-bottom: 1rem;">
                            <div style="width: 60px; height: 60px; background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%); border-radius: 50%; display: flex; align-items: center; justify-content: center; color: white; font-size: 1.8rem;">
                                <i class="fas fa-store"></i>
                            </div>
                            <div style="flex: 1;">
                                <h3 style="margin: 0; color: #2d3748;">{{ $c->nombre }}</h3>
                                <p style="margin: 0; color: #718096; font-size: 0.9rem;">
                                    <i class="fas fa-envelope"></i> {{ $c->email }}
                                </p>
                            </div>
                        </div>

                        <div style="display: grid; grid-template-columns: repeat(2, 1fr); gap: 1rem; margin-top: 1.5rem;">
                            <div style="padding: 1rem; background: #f8f9fa; border-radius: 8px; text-align: center;">
                                <div style="font-size: 2rem; font-weight: 700; color: #667eea;">
                                    {{ $c->ofertas->count() }}
                                </div>
                                <div style="font-size: 0.85rem; color: #718096;">Ofertas Activas</div>
                            </div>
                            <div style="padding: 1rem; background: #f8f9fa; border-radius: 8px; text-align: center;">
                                <div style="font-size: 2rem; font-weight: 700; color: #28a745;">
                                    {{ $c->puntos ?? 0 }}
                                </div>
                                <div style="font-size: 0.85rem; color: #718096;">Puntos</div>
                            </div>
                        </div>

                        @if($c->ofertas->count() > 0)
                            <div style="margin-top: 1.5rem; padding-top: 1.5rem; border-top: 1px solid #e2e8f0;">
                                <h4 style="font-size: 0.9rem; color: #4a5568; margin-bottom: 0.75rem;">
                                    <i class="fas fa-tags"></i> Ofertas:
                                </h4>
                                @foreach($c->ofertas as $oferta)
                                    <div style="padding: 0.5rem; background: #f8f9fa; border-radius: 6px; margin-bottom: 0.5rem; font-size: 0.85rem;">
                                        <strong>{{ $oferta->titulo }}</strong> - 
                                        <span style="color: #667eea;"><i class="fas fa-star"></i> {{ $oferta->puntos }} pts</span>
                                    </div>
                                @endforeach
                            </div>
                        @endif
                    </div>
                @empty
                    <div class="empty-state-modern" style="grid-column: 1 / -1;">
                        <i class="fas fa-store-slash"></i>
                        <h3>No hay comercios registrados</h3>
                        <p>Los comercios aparecerán aquí cuando se registren</p>
                    </div>
                @endforelse
            </div>
        </div>
    </div>
</main>

<footer>
    <p>© {{ date('Y') }} Quibdó Seguro - Panel de Administración</p>
</footer>
</body>
</html>
