<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Gestión de Ofertas - Quibdó Seguro</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="{{ asset('assets/css/styles.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/pages.css') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body>
@include('components.header')

<main class="ofertas-main">
    <!-- Hero Section -->
    <div class="page-hero">
        <h1><i class="fas fa-tags"></i> Gestión de Ofertas</h1>
        <p>Crea y administra las ofertas de tu comercio</p>
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

        <!-- Formulario para crear oferta -->
        <div class="modern-card">
            <div class="card-header">
                <h2><i class="fas fa-plus-circle"></i> Crear Nueva Oferta</h2>
            </div>
            <form action="{{ url('/comercio/ofertas') }}" method="POST" class="modern-form">
                @csrf
                <div class="form-row">
                    <div class="form-group" style="flex: 2;">
                        <label><i class="fas fa-tag"></i> Título de la Oferta</label>
                        <input type="text" name="titulo" placeholder="Ej: 20% de descuento en productos" required class="form-control">
                    </div>
                    <div class="form-group" style="flex: 1;">
                        <label><i class="fas fa-star"></i> Puntos Requeridos</label>
                        <input type="number" name="puntos" placeholder="100" min="1" required class="form-control">
                    </div>
                </div>
                
                <div class="form-group">
                    <label><i class="fas fa-align-left"></i> Descripción Detallada</label>
                    <textarea name="descripcion" rows="4" placeholder="Describe los términos y condiciones de tu oferta..." required class="form-control"></textarea>
                </div>

                <button type="submit" class="btn-modern">
                    <i class="fas fa-plus"></i> Crear Oferta
                </button>
            </form>
        </div>

        <!-- Listado de ofertas -->
        <div class="modern-card">
            <div class="card-header">
                <h2><i class="fas fa-list"></i> Mis Ofertas Activas</h2>
                <span class="badge-modern badge-info">{{ $ofertas->count() }} ofertas</span>
            </div>

            @if($ofertas->count() > 0)
                <div class="ofertas-grid">
                    @foreach($ofertas as $oferta)
                        <div class="oferta-card-admin">
                            <div class="oferta-card-header">
                                <div class="oferta-info">
                                    <h3>{{ $oferta->titulo }}</h3>
                                    <p class="oferta-descripcion">{{ $oferta->descripcion }}</p>
                                </div>
                                <div class="puntos-badge-large">
                                    <i class="fas fa-star"></i>
                                    {{ $oferta->puntos }}
                                </div>
                            </div>

                            <div class="oferta-stats">
                                @php
                                    $canjesOferta = \App\Models\Canje::where('id_oferta', $oferta->_id)->count();
                                    $canjesValidados = \App\Models\Canje::where('id_oferta', $oferta->_id)->where('estado', 'validado')->count();
                                @endphp
                                
                                <div class="stat-item">
                                    <i class="fas fa-ticket-alt"></i>
                                    <div>
                                        <span class="stat-number">{{ $canjesOferta }}</span>
                                        <span class="stat-label">Total Canjes</span>
                                    </div>
                                </div>
                                
                                <div class="stat-item">
                                    <i class="fas fa-check-circle"></i>
                                    <div>
                                        <span class="stat-number">{{ $canjesValidados }}</span>
                                        <span class="stat-label">Validados</span>
                                    </div>
                                </div>
                            </div>

                            <div class="oferta-actions">
                                <form action="{{ url('/comercio/ofertas/'.$oferta->_id) }}" method="POST" onsubmit="return confirm('¿Estás seguro de eliminar esta oferta?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn-danger">
                                        <i class="fas fa-trash"></i> Eliminar
                                    </button>
                                </form>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="empty-state-modern">
                    <i class="fas fa-tags"></i>
                    <h3>No tienes ofertas creadas</h3>
                    <p>Crea tu primera oferta usando el formulario de arriba</p>
                </div>
            @endif
        </div>

        <!-- Información útil -->
        <div class="info-card">
            <i class="fas fa-lightbulb"></i>
            <div>
                <strong>Consejos para Crear Ofertas Exitosas</strong>
                <ul>
                    <li>Usa títulos claros y atractivos</li>
                    <li>Especifica claramente los términos y condiciones</li>
                    <li>Ajusta los puntos según el valor de la oferta</li>
                    <li>Mantén tus ofertas actualizadas</li>
                </ul>
            </div>
        </div>
    </div>
</main>

<footer>
    <p>© {{ date('Y') }} Quibdó Seguro - Todos los derechos reservados.</p>
</footer>

<style>
.ofertas-main {
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

.ofertas-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(400px, 1fr));
    gap: 1.5rem;
}

.oferta-card-admin {
    background: white;
    border-radius: 12px;
    overflow: hidden;
    box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
    transition: transform 0.3s, box-shadow 0.3s;
    border-left: 4px solid #667eea;
}

.oferta-card-admin:hover {
    transform: translateY(-2px);
    box-shadow: 0 5px 20px rgba(0, 0, 0, 0.1);
}

.oferta-card-header {
    padding: 1.5rem;
    display: flex;
    justify-content: space-between;
    align-items: flex-start;
    gap: 1rem;
}

.oferta-info {
    flex: 1;
}

.oferta-info h3 {
    font-size: 1.2rem;
    color: #2d3748;
    margin-bottom: 0.5rem;
}

.oferta-descripcion {
    color: #718096;
    font-size: 0.9rem;
    line-height: 1.5;
    margin: 0;
}

.puntos-badge-large {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    padding: 0.75rem 1rem;
    border-radius: 12px;
    font-weight: 700;
    display: flex;
    align-items: center;
    gap: 0.5rem;
    font-size: 1.1rem;
}

.oferta-stats {
    padding: 1rem 1.5rem;
    background: #f8f9fa;
    display: flex;
    gap: 2rem;
}

.stat-item {
    display: flex;
    align-items: center;
    gap: 0.75rem;
}

.stat-item i {
    width: 35px;
    height: 35px;
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    border-radius: 8px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 0.9rem;
}

.stat-item div {
    display: flex;
    flex-direction: column;
}

.stat-number {
    font-size: 1.3rem;
    font-weight: 700;
    color: #2d3748;
    line-height: 1;
}

.stat-label {
    font-size: 0.8rem;
    color: #718096;
}

.oferta-actions {
    padding: 1rem 1.5rem;
    border-top: 1px solid #e2e8f0;
}

.btn-danger {
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

.btn-danger:hover {
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
    margin-bottom: 0.75rem;
    color: #2d3748;
}

.info-card ul {
    margin: 0;
    padding-left: 1.5rem;
    color: #4a5568;
}

.info-card li {
    margin-bottom: 0.25rem;
}

@media (max-width: 768px) {
    .form-row {
        flex-direction: column;
    }
    
    .ofertas-grid {
        grid-template-columns: 1fr;
    }
    
    .oferta-card-header {
        flex-direction: column;
        align-items: flex-start;
    }
    
    .oferta-stats {
        flex-direction: column;
        gap: 1rem;
    }
}
</style>
</body>
</html>
