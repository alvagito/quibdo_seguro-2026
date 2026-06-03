<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Gestión de Recompensas - Admin</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="{{ asset('assets/css/styles.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/pages.css') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body>
@include('components.header')

<main class="admin-main">
    <div class="page-hero">
        <h1><i class="fas fa-gift"></i> Recompensas del Sistema</h1>
        <p>Administra las recompensas especiales que otorga el sistema</p>
    </div>

    <div class="modern-container">
        <!-- Mensajes -->
        @if(session('success'))
            <div class="alert-modern alert-modern-success">
                <i class="fas fa-check-circle"></i>
                <span>{{ session('success') }}</span>
            </div>
        @endif
        @if(session('error'))
            <div class="alert-modern alert-modern-error">
                <i class="fas fa-times-circle"></i>
                <span>{{ session('error') }}</span>
            </div>
        @endif

        <!-- Formulario para crear recompensa -->
        <div class="modern-card">
            <div class="card-header">
                <h2><i class="fas fa-plus-circle"></i> Crear Nueva Recompensa</h2>
            </div>
            <form action="{{ url('/admin/recompensas') }}" method="POST" class="modern-form">
                @csrf
                <div class="form-row">
                    <div class="form-group" style="flex: 2;">
                        <label><i class="fas fa-award"></i> Nombre de la Recompensa</label>
                        <input type="text" name="nombre" placeholder="Ej: Certificado de Participación" required class="form-control">
                    </div>
                    <div class="form-group" style="flex: 1;">
                        <label><i class="fas fa-star"></i> Puntos Requeridos</label>
                        <input type="number" name="puntos" placeholder="100" min="1" required class="form-control">
                    </div>
                    <div class="form-group" style="flex: 0 0 auto; align-self: flex-end;">
                        <button type="submit" class="btn-modern">
                            <i class="fas fa-plus"></i> Agregar
                        </button>
                    </div>
                </div>
            </form>
        </div>

        <!-- Lista de recompensas -->
        <div class="modern-card">
            <div class="card-header">
                <h2><i class="fas fa-list"></i> Recompensas Activas</h2>
                <span class="badge-modern badge-info">{{ $recompensas->count() }} recompensas</span>
            </div>

            @if($recompensas->count() > 0)
                <table class="modern-table">
                    <thead>
                        <tr>
                            <th><i class="fas fa-hashtag"></i> ID</th>
                            <th><i class="fas fa-award"></i> Nombre</th>
                            <th><i class="fas fa-star"></i> Puntos</th>
                            <th><i class="fas fa-calendar"></i> Creada</th>
                            <th><i class="fas fa-cog"></i> Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($recompensas as $rec)
                            <tr>
                                <td><code>{{ substr($rec->_id, -6) }}</code></td>
                                <td><strong>{{ $rec->nombre }}</strong></td>
                                <td>
                                    <span class="badge-modern badge-primary">
                                        <i class="fas fa-star"></i> {{ $rec->puntos }}
                                    </span>
                                </td>
                                <td>{{ $rec->created_at ? $rec->created_at->format('d/m/Y') : 'N/A' }}</td>
                                <td>
                                    <form action="{{ url('/admin/recompensas/'.$rec->_id) }}" method="POST" style="display:inline;" onsubmit="return confirm('¿Estás seguro de eliminar esta recompensa?');">
                                        @csrf 
                                        @method('DELETE')
                                        <button type="submit" class="btn-danger-small">
                                            <i class="fas fa-trash"></i> Eliminar
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            @else
                <div class="empty-state-modern">
                    <i class="fas fa-gift"></i>
                    <h3>No hay recompensas creadas</h3>
                    <p>Crea la primera recompensa usando el formulario de arriba</p>
                </div>
            @endif
        </div>

        <!-- Información adicional -->
        <div class="info-card">
            <i class="fas fa-info-circle"></i>
            <div>
                <strong>Sobre las Recompensas del Sistema</strong>
                <p>Las recompensas del sistema son premios especiales que los usuarios pueden canjear con sus puntos. A diferencia de las ofertas de comercios, estas no generan código QR y son canjeadas instantáneamente. Para gestionar las ofertas de comercios, ve a la sección "Ofertas".</p>
            </div>
        </div>
    </div>
</main>

<footer>
    <p>© {{ date('Y') }} Quibdó Seguro - Todos los derechos reservados.</p>
</footer>

<style>
.admin-main {
    background: #f5f7fa;
    min-height: 100vh;
}

.form-row {
    display: flex;
    gap: 1rem;
    align-items: flex-start;
}

.form-group {
    display: flex;
    flex-direction: column;
    flex: 1;
}

.form-group label {
    font-weight: 600;
    margin-bottom: 0.5rem;
    color: #2d3748;
    display: flex;
    align-items: center;
    gap: 0.5rem;
}

.form-group label i {
    color: #667eea;
}

.form-control {
    padding: 0.75rem;
    border: 2px solid #e2e8f0;
    border-radius: 8px;
    font-size: 1rem;
    transition: border-color 0.3s;
}

.form-control:focus {
    outline: none;
    border-color: #667eea;
}

.btn-danger-small {
    padding: 0.5rem 1rem;
    background: #e53e3e;
    color: white;
    border: none;
    border-radius: 6px;
    cursor: pointer;
    font-size: 0.9rem;
    transition: background 0.3s;
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
}

.btn-danger-small:hover {
    background: #c53030;
}

.info-card {
    background: linear-gradient(135deg, #e0e7ff 0%, #f0f4ff 100%);
    border-left: 4px solid #667eea;
    padding: 1.5rem;
    border-radius: 8px;
    display: flex;
    gap: 1rem;
    align-items: flex-start;
}

.info-card i {
    font-size: 1.5rem;
    color: #667eea;
    margin-top: 0.25rem;
}

.info-card strong {
    display: block;
    margin-bottom: 0.5rem;
    color: #2d3748;
}

.info-card p {
    color: #4a5568;
    line-height: 1.6;
    margin: 0;
}

code {
    background: #f7fafc;
    padding: 0.25rem 0.5rem;
    border-radius: 4px;
    font-family: 'Courier New', monospace;
    color: #667eea;
    font-size: 0.9rem;
}

@media (max-width: 768px) {
    .form-row {
        flex-direction: column;
    }
    
    .form-group {
        width: 100%;
    }
}
</style>
</body>
</html>
