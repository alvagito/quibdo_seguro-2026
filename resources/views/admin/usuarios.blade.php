<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Gestión de Usuarios - Admin</title>
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
        <h1><i class="fas fa-users-cog"></i> Gestión de Usuarios</h1>
        <p>Administra todos los usuarios de la plataforma</p>
    </div>

    <div class="modern-container">
        @if(session('success'))
            <div class="alert-modern alert-modern-success">
                <i class="fas fa-check-circle"></i>
                <span>{{ session('success') }}</span>
            </div>
        @endif

        <!-- Filtros de Búsqueda -->
        <div class="modern-card" style="margin-bottom: 2rem;">
            <form method="GET" action="{{ url('/admin/usuarios') }}" style="display: flex; gap: 1rem; flex-wrap: wrap; align-items: end;">
                <div class="modern-form-group" style="flex: 1; min-width: 250px; margin-bottom: 0;">
                    <label><i class="fas fa-search"></i> Buscar</label>
                    <input type="text" name="buscar" placeholder="Nombre o email..." value="{{ request('buscar') }}">
                </div>
                <div class="modern-form-group" style="flex: 1; min-width: 200px; margin-bottom: 0;">
                    <label><i class="fas fa-filter"></i> Rol</label>
                    <select name="rol" onchange="this.form.submit()">
                        <option value="">Todos los roles</option>
                        <option value="normal" {{ request('rol') == 'normal' ? 'selected' : '' }}>Normal</option>
                        <option value="admin" {{ request('rol') == 'admin' ? 'selected' : '' }}>Admin</option>
                        <option value="autoridad" {{ request('rol') == 'autoridad' ? 'selected' : '' }}>Autoridad</option>
                        <option value="comercio" {{ request('rol') == 'comercio' ? 'selected' : '' }}>Comercio</option>
                    </select>
                </div>
                <button type="submit" class="btn-modern">
                    <i class="fas fa-search"></i> Buscar
                </button>
            </form>
        </div>

        <!-- Tabla de Usuarios -->
        <div class="modern-card">
            <div class="modern-card-header">
                <h2><i class="fas fa-list"></i> Lista de Usuarios ({{ $usuarios->total() }})</h2>
            </div>

            <table class="modern-table">
                <thead>
                    <tr>
                        <th><i class="fas fa-user"></i> Nombre</th>
                        <th><i class="fas fa-envelope"></i> Email</th>
                        <th><i class="fas fa-shield-alt"></i> Rol</th>
                        <th><i class="fas fa-star"></i> Puntos</th>
                        <th><i class="fas fa-calendar"></i> Registro</th>
                        <th><i class="fas fa-cog"></i> Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($usuarios as $u)
                        <tr>
                            <td><strong>{{ $u->nombre }}</strong></td>
                            <td>{{ $u->email }}</td>
                            <td>
                                <span class="badge-modern 
                                    @if($u->rol == 'admin') badge-danger
                                    @elseif($u->rol == 'autoridad') badge-info
                                    @elseif($u->rol == 'comercio') badge-success
                                    @else badge-warning
                                    @endif">
                                    {{ ucfirst($u->rol) }}
                                </span>
                            </td>
                            <td>
                                <span style="color: #667eea; font-weight: 600;">
                                    <i class="fas fa-star"></i> {{ $u->puntos ?? 0 }}
                                </span>
                            </td>
                            <td>{{ $u->created_at->format('d/m/Y') }}</td>
                            <td>
                                <div style="display: flex; gap: 0.5rem;">
                                    <a href="{{ url('/admin/usuarios/'.$u->_id.'/editar') }}" class="btn-modern" style="padding: 0.5rem 1rem; font-size: 0.9rem;">
                                        <i class="fas fa-edit"></i> Editar
                                    </a>
                                    <form action="{{ url('/admin/usuarios/'.$u->_id) }}" method="POST" style="display:inline;" onsubmit="return confirm('¿Estás seguro de eliminar este usuario?');">
                                        @csrf 
                                        @method('DELETE')
                                        <button type="submit" style="padding: 0.5rem 1rem; background: #e74c3c; color: white; border: none; border-radius: 6px; cursor: pointer; font-size: 0.9rem; display: flex; align-items: center; gap: 0.5rem;">
                                            <i class="fas fa-trash"></i> Eliminar
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" style="text-align: center; padding: 2rem; color: #718096;">
                                <i class="fas fa-users-slash" style="font-size: 2rem; margin-bottom: 1rem; display: block;"></i>
                                No se encontraron usuarios
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>

            <!-- Paginación -->
            @if($usuarios->hasPages())
                <div style="padding: 1.5rem; border-top: 1px solid #e2e8f0;">
                    {{ $usuarios->links() }}
                </div>
            @endif
        </div>
    </div>
</main>

<footer>
    <p>© {{ date('Y') }} Quibdó Seguro - Panel de Administración</p>
</footer>
</body>
</html>
