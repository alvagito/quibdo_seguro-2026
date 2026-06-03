<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Editar Oferta - Admin</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="{{ asset('assets/css/styles.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/pages.css') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body>
@include('components.header')

<main class="admin-main">
    <div class="page-hero">
        <h1><i class="fas fa-edit"></i> Editar Oferta</h1>
        <p>Modifica los detalles de la oferta del comercio</p>
    </div>

    <div class="modern-container">
        <!-- Mensajes -->
        @if(session('success'))
            <div class="alert-modern alert-modern-success">
                <i class="fas fa-check-circle"></i>
                <span>{{ session('success') }}</span>
            </div>
        @endif
        @if($errors->any())
            <div class="alert-modern alert-modern-error">
                <i class="fas fa-exclamation-triangle"></i>
                <span>Por favor corrige los errores en el formulario</span>
            </div>
        @endif

        <!-- Información del Comercio -->
        <div class="modern-card">
            <div class="card-header">
                <h2><i class="fas fa-store"></i> Información del Comercio</h2>
            </div>
            <div class="comercio-info-edit">
                <div class="comercio-avatar-large">
                    <i class="fas fa-store"></i>
                </div>
                <div class="comercio-details-large">
                    <h3>{{ $oferta->comercio->nombre }}</h3>
                    <p><i class="fas fa-envelope"></i> {{ $oferta->comercio->email }}</p>
                    <p><i class="fas fa-calendar"></i> Miembro desde {{ $oferta->comercio->created_at ? $oferta->comercio->created_at->format('d/m/Y') : 'N/A' }}</p>
                </div>
            </div>
        </div>

        <!-- Formulario de Edición -->
        <div class="modern-card">
            <div class="card-header">
                <h2><i class="fas fa-edit"></i> Editar Oferta</h2>
            </div>
            
            <form action="{{ url('/admin/ofertas/'.$oferta->_id) }}" method="POST" class="modern-form">
                @csrf
                @method('PUT')
                
                <div class="form-group">
                    <label><i class="fas fa-tag"></i> Título de la Oferta</label>
                    <input type="text" name="titulo" value="{{ old('titulo', $oferta->titulo) }}" required class="form-control @error('titulo') error @enderror">
                    @error('titulo')
                        <span class="error-message">{{ $message }}</span>
                    @enderror
                </div>

                <div class="form-group">
                    <label><i class="fas fa-align-left"></i> Descripción</label>
                    <textarea name="descripcion" rows="4" required class="form-control @error('descripcion') error @enderror">{{ old('descripcion', $oferta->descripcion) }}</textarea>
                    @error('descripcion')
                        <span class="error-message">{{ $message }}</span>
                    @enderror
                </div>

                <div class="form-group">
                    <label><i class="fas fa-star"></i> Puntos Requeridos</label>
                    <input type="number" name="puntos" value="{{ old('puntos', $oferta->puntos) }}" min="1" required class="form-control @error('puntos') error @enderror">
                    @error('puntos')
                        <span class="error-message">{{ $message }}</span>
                    @enderror
                </div>

                <div class="form-actions">
                    <button type="submit" class="btn-modern">
                        <i class="fas fa-save"></i> Guardar Cambios
                    </button>
                    <a href="{{ url('/admin/ofertas') }}" class="btn-secondary">
                        <i class="fas fa-times"></i> Cancelar
                    </a>
                </div>
            </form>
        </div>

        <!-- Estadísticas de la Oferta -->
        <div class="modern-card">
            <div class="card-header">
                <h2><i class="fas fa-chart-bar"></i> Estadísticas de la Oferta</h2>
            </div>
            
            @php
                $canjesOferta = \App\Models\Canje::where('id_oferta', $oferta->_id)->count();
                $canjesValidados = \App\Models\Canje::where('id_oferta', $oferta->_id)->where('estado', 'validado')->count();
                $canjesPendientes = \App\Models\Canje::where('id_oferta', $oferta->_id)->where('estado', 'pendiente')->count();
                $puntosRedimidos = \App\Models\Canje::where('id_oferta', $oferta->_id)->where('estado', 'validado')->sum('puntos_canjeados');
            @endphp

            <div class="stats-grid-edit">
                <div class="stat-item-edit">
                    <div class="stat-icon-edit stat-primary">
                        <i class="fas fa-ticket-alt"></i>
                    </div>
                    <div class="stat-content-edit">
                        <div class="stat-number-edit">{{ $canjesOferta }}</div>
                        <div class="stat-label-edit">Total Canjes</div>
                    </div>
                </div>

                <div class="stat-item-edit">
                    <div class="stat-icon-edit stat-success">
                        <i class="fas fa-check-circle"></i>
                    </div>
                    <div class="stat-content-edit">
                        <div class="stat-number-edit">{{ $canjesValidados }}</div>
                        <div class="stat-label-edit">Validados</div>
                    </div>
                </div>

                <div class="stat-item-edit">
                    <div class="stat-icon-edit stat-warning">
                        <i class="fas fa-hourglass-half"></i>
                    </div>
                    <div class="stat-content-edit">
                        <div class="stat-number-edit">{{ $canjesPendientes }}</div>
                        <div class="stat-label-edit">Pendientes</div>
                    </div>
                </div>

                <div class="stat-item-edit">
                    <div class="stat-icon-edit stat-info">
                        <i class="fas fa-star"></i>
                    </div>
                    <div class="stat-content-edit">
                        <div class="stat-number-edit">{{ $puntosRedimidos }}</div>
                        <div class="stat-label-edit">Puntos Redimidos</div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Información adicional -->
        <div class="info-card">
            <i class="fas fa-info-circle"></i>
            <div>
                <strong>Sobre la Edición de Ofertas</strong>
                <p>Al editar una oferta, los cambios se aplicarán inmediatamente. Los canjes ya realizados no se verán afectados, pero los nuevos canjes utilizarán la información actualizada.</p>
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

.comercio-info-edit {
    display: flex;
    align-items: center;
    gap: 1.5rem;
}

.comercio-avatar-large {
    width: 80px;
    height: 80px;
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 2rem;
}

.comercio-details-large h3 {
    margin: 0 0 0.5rem 0;
    color: #2d3748;
    font-size: 1.5rem;
}

.comercio-details-large p {
    margin: 0.25rem 0;
    color: #718096;
    display: flex;
    align-items: center;
    gap: 0.5rem;
}

.comercio-details-large i {
    color: #667eea;
    width: 16px;
}

.form-group {
    margin-bottom: 1.5rem;
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
    width: 100%;
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

.form-control.error {
    border-color: #e53e3e;
}

.error-message {
    color: #e53e3e;
    font-size: 0.85rem;
    margin-top: 0.25rem;
    display: block;
}

.form-actions {
    display: flex;
    gap: 1rem;
    margin-top: 2rem;
}

.btn-secondary {
    padding: 0.75rem 1.5rem;
    background: #e2e8f0;
    color: #4a5568;
    text-decoration: none;
    border-radius: 8px;
    font-weight: 600;
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
    transition: background 0.3s;
}

.btn-secondary:hover {
    background: #cbd5e0;
    text-decoration: none;
    color: #4a5568;
}

.stats-grid-edit {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
    gap: 1.5rem;
}

.stat-item-edit {
    display: flex;
    align-items: center;
    gap: 1rem;
    padding: 1rem;
    background: #f8f9fa;
    border-radius: 8px;
}

.stat-icon-edit {
    width: 50px;
    height: 50px;
    border-radius: 10px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.2rem;
    color: white;
}

.stat-primary { background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); }
.stat-success { background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%); }
.stat-warning { background: linear-gradient(135deg, #ffecd2 0%, #fcb69f 100%); color: #8b4513 !important; }
.stat-info { background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%); }

.stat-content-edit {
    flex: 1;
}

.stat-number-edit {
    font-size: 1.5rem;
    font-weight: 700;
    color: #2d3748;
    line-height: 1;
}

.stat-label-edit {
    font-size: 0.85rem;
    color: #718096;
    margin-top: 0.25rem;
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

@media (max-width: 768px) {
    .comercio-info-edit {
        flex-direction: column;
        text-align: center;
    }
    
    .stats-grid-edit {
        grid-template-columns: 1fr;
    }
    
    .form-actions {
        flex-direction: column;
    }
}
</style>
</body>
</html>