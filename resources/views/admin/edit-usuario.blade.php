<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Usuario - Admin</title>
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
            <h1><i class="fas fa-user-edit"></i> Editar Usuario</h1>
            <p>Modifica la información del usuario</p>
        </div>

        <div class="modern-container">
            <div class="modern-card">
                <div class="modern-card-header">
                    <h2><i class="fas fa-edit"></i> Información del Usuario</h2>
                    <a href="{{ url('/admin/usuarios') }}" class="btn-modern-secondary">
                        <i class="fas fa-arrow-left"></i> Volver
                    </a>
                </div>

                @if ($errors->any())
                    <div class="alert-modern alert-modern-error">
                        <i class="fas fa-times-circle"></i>
                        <div>
                            @foreach ($errors->all() as $error)
                                <div>{{ $error }}</div>
                            @endforeach
                        </div>
                    </div>
                @endif

                <form action="{{ url('/admin/usuarios/' . $usuario->_id) }}" method="POST">
                    @csrf
                    @method('PUT')

                    <div class="modern-form-group">
                        <label for="nombre">
                            <i class="fas fa-user"></i> Nombre Completo
                        </label>
                        <input type="text" id="nombre" name="nombre" value="{{ old('nombre', $usuario->nombre) }}" required>
                    </div>

                    <div class="modern-form-group">
                        <label for="email">
                            <i class="fas fa-envelope"></i> Correo Electrónico
                        </label>
                        <input type="email" id="email" name="email" value="{{ old('email', $usuario->email) }}" required>
                    </div>

                    <div class="modern-form-group">
                        <label for="rol">
                            <i class="fas fa-shield-alt"></i> Rol
                        </label>
                        <select id="rol" name="rol" required>
                            <option value="normal" {{ $usuario->rol == 'normal' ? 'selected' : '' }}>Usuario Normal</option>
                            <option value="admin" {{ $usuario->rol == 'admin' ? 'selected' : '' }}>Administrador</option>
                            <option value="autoridad" {{ $usuario->rol == 'autoridad' ? 'selected' : '' }}>Autoridad</option>
                            <option value="comercio" {{ $usuario->rol == 'comercio' ? 'selected' : '' }}>Comercio</option>
                        </select>
                    </div>

                    <div class="modern-form-group">
                        <label for="puntos">
                            <i class="fas fa-star"></i> Puntos
                        </label>
                        <input type="number" id="puntos" name="puntos" value="{{ old('puntos', $usuario->puntos ?? 0) }}" min="0" required>
                    </div>

                    <div class="modern-form-group">
                        <label for="password">
                            <i class="fas fa-lock"></i> Nueva Contraseña (opcional)
                        </label>
                        <input type="password" id="password" name="password" placeholder="Dejar en blanco para mantener la actual">
                        <small style="color: #718096; font-size: 0.85rem;">Solo completa si deseas cambiar la contraseña</small>
                    </div>

                    <div class="modern-form-group">
                        <label for="password_confirmation">
                            <i class="fas fa-lock"></i> Confirmar Nueva Contraseña
                        </label>
                        <input type="password" id="password_confirmation" name="password_confirmation" placeholder="Confirma la nueva contraseña">
                    </div>

                    <div style="display: flex; gap: 1rem; margin-top: 2rem;">
                        <button type="submit" class="btn-modern" style="flex: 1;">
                            <i class="fas fa-save"></i> Guardar Cambios
                        </button>
                        <a href="{{ url('/admin/usuarios') }}" class="btn-modern-secondary" style="flex: 1; text-align: center; text-decoration: none; display: flex; align-items: center; justify-content: center;">
                            <i class="fas fa-times"></i> Cancelar
                        </a>
                    </div>
                </form>
            </div>

            <!-- Información Adicional -->
            <div class="modern-card" style="margin-top: 2rem;">
                <div class="modern-card-header">
                    <h2><i class="fas fa-info-circle"></i> Información Adicional</h2>
                </div>
                <div style="padding: 1.5rem;">
                    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 1.5rem;">
                        <div style="padding: 1rem; background: #f8f9fa; border-radius: 8px;">
                            <div style="color: #718096; font-size: 0.9rem; margin-bottom: 0.5rem;">
                                <i class="fas fa-calendar-plus"></i> Fecha de Registro
                            </div>
                            <div style="font-weight: 600; color: #2d3748;">
                                {{ $usuario->created_at->format('d/m/Y H:i') }}
                            </div>
                        </div>
                        <div style="padding: 1rem; background: #f8f9fa; border-radius: 8px;">
                            <div style="color: #718096; font-size: 0.9rem; margin-bottom: 0.5rem;">
                                <i class="fas fa-clock"></i> Última Actualización
                            </div>
                            <div style="font-weight: 600; color: #2d3748;">
                                {{ $usuario->updated_at->format('d/m/Y H:i') }}
                            </div>
                        </div>
                        <div style="padding: 1rem; background: #f8f9fa; border-radius: 8px;">
                            <div style="color: #718096; font-size: 0.9rem; margin-bottom: 0.5rem;">
                                <i class="fas fa-exclamation-triangle"></i> Reportes
                            </div>
                            <div style="font-weight: 600; color: #2d3748;">
                                {{ $usuario->incidentes()->count() }} reportes
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>

    <footer>
        <p>© {{ date('Y') }} Quibdó Seguro - Panel de Administración</p>
    </footer>
</body>
</html>
