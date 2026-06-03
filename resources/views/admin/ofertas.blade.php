<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Gestión de Ofertas - Admin</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="{{ asset('assets/css/styles.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/pages.css') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body>
@include('components.header')

<main class="admin-main">
    <div class="page-hero">
        <h1><i class="fas fa-tags"></i> Gestión de Ofertas de Comercios</h1>
        <p>Administra y modera las ofertas publicadas por los comercios aliados</p>
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

        <!-- Estadísticas Rápidas -->
        <div class="stats-grid">
            <div class="stat-card stat-primary">
                <div class="stat-icon">
                    <i class="fas fa-tags"></i>
                </div>
                <div class="stat-content">
                    <div class="stat-number">{{ $ofertas->count() }}</div>
                    <div class="stat-label">Total Ofertas</div>
                </div>
            </div>

            <div class="stat-card stat-success">
                <div class="stat-icon">
                    <i class="fas fa-store"></i>
                </div>
                <div class="stat-content">
                    <div class="stat-number">{{ $ofertas->groupBy('id_comercio')->count() }}</div>
                    <div class="stat-label">Comercios Activos</div>
                </div>
            </div>

            <div class="stat-card stat-info">
                <div class="stat-icon">
                    <i class="fas fa-star"></i>
                </div>
                <div class="stat-content">
                    <div class="stat-number">{{ $ofertas->avg('puntos') ? round($ofertas->avg('puntos')) : 0 }}</div>
                    <div class="stat-label">Puntos Promedio</div>
                </div>
            </div>

            <div class="stat-card stat-warning">
                <div class="stat-icon">
                    <i class="fas fa-ticket-alt"></i>
                </div>
                <div class="stat-content">
                    @php
                        $totalCanjes = \App\Models\Canje::whereIn('id_oferta', $ofertas->pluck('_id'))->count();
                    @endphp
                    <div class="stat-number">{{ $totalCanjes }}</div>
                    <div class="stat-label">Total Canjes</div>
                </div>
            </div>
        </div>

        <!-- Lista de ofertas -->
        <div class="modern-card">
            <div class="card-header">
                <h2><i class="fas fa-list"></i> Ofertas de Comercios</h2>
                <span class="badge-modern badge-info">{{ $ofertas->count() }} ofertas</span>
            </div>

            @if($ofertas->count() > 0)
                <div class="ofertas-admin-grid">
                    @foreach($ofertas as $oferta)
                        <div class="oferta-admin-card">
                            <div class="oferta-admin-header">
                                <div class="comercio-info">
                                    <div class="comercio-avatar">
                                        <i class="fas fa-store"></i>
                                    </div>
                                    <div class="comercio-details">
                                        <h4>{{ $oferta->comercio->nombre }}</h4>
                                        <p>{{ $oferta->comercio->email }}</p>
                                    </div>
                                </div>
                                <div class="puntos-badge-admin">
                                    <i class="fas fa-star"></i>
                                    {{ $oferta->puntos }}
                                </div>
                            </div>

                            <div class="oferta-admin-body">
                                <h3>{{ $oferta->titulo }}</h3>
                                <p class="oferta-descripcion">{{ $oferta->descripcion }}</p>
                            </div>

                            <div class="oferta-admin-stats">
                                @php
                                    $canjesOferta = \App\Models\Canje::where('id_oferta', $oferta->_id)->count();
                                    $canjesValidados = \App\Models\Canje::where('id_oferta', $oferta->_id)->where('estado', 'validado')->count();
                                @endphp
                                
                                <div class="stat-mini">
                                    <i class="fas fa-ticket-alt"></i>
                                    <span>{{ $canjesOferta }} canjes</span>
                                </div>
                                <div class="stat-mini">
                                    <i class="fas fa-check-circle"></i>
                                    <span>{{ $canjesValidados }} validados</span>
                                </div>
                                <div class="stat-mini">
                                    <i class="fas fa-calendar"></i>
                                    <span>{{ $oferta->created_at ? $oferta->created_at->format('d/m/Y') : 'N/A' }}</span>
                                </div>
                            </div>

                            <div class="oferta-admin-actions">
                                <a href="{{ url('/admin/ofertas/'.$oferta->_id.'/edit') }}" class="btn-edit">
                                    <i class="fas fa-edit"></i> Editar
                                </a>
                                <form action="{{ url('/admin/ofertas/'.$oferta->_id) }}" method="POST" style="display:inline;" onsubmit="return confirm('¿Estás seguro de eliminar esta oferta?');">
                                    @csrf 
                                    @method('DELETE')
                                    <button type="submit" class="btn-delete">
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
                    <h3>No hay ofertas publicadas</h3>
                    <p>Los comercios aún no han publicado ofertas</p>
                </div>
            @endif
        </div>

        <!-- Información adicional -->
        <div class="info-card">
            <i class="fas fa-info-circle"></i>
            <div>
                <strong>Gestión de Ofertas de Comercios</strong>
                <p>Desde aquí puedes moderar las ofertas que publican los comercios aliados. Puedes editarlas para corregir información o eliminarlas si no cumplen con las políticas. Los comercios crean sus propias ofertas desde su panel.</p>
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

.stats-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
    gap: 1.5rem;
    margin-bottom: 2rem;
}

.stat-card {
    background: white;
    padding: 1.5rem;
    border-radius: 12px;
    box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
    display: flex;
    align-items: center;
    gap: 1rem;
    transition: transform 0.3s, box-shadow 0.3s;
}

.stat-card:hover {
    transform: translateY(-2px);
    box-shadow: 0 5px 20px rgba(0, 0, 0, 0.1);
}

.stat-icon {
    width: 50px;
    height: 50px;
    border-radius: 10px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.3rem;
    color: white;
}

.stat-primary .stat-icon { background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); }
.stat-success .stat-icon { background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%); }
.stat-info .stat-icon { background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%); }
.stat-warning .stat-icon { background: linear-gradient(135deg, #ffecd2 0%, #fcb69f 100%); color: #8b4513 !important; }

.stat-content {
    flex: 1;
}

.stat-number {
    font-size: 1.8rem;
    font-weight: 700;
    color: #2d3748;
    line-height: 1;
}

.stat-label {
    font-size: 0.9rem;
    color: #718096;
    margin-top: 0.25rem;
}

.ofertas-admin-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(400px, 1fr));
    gap: 1.5rem;
}

.oferta-admin-card {
    background: white;
    border-radius: 12px;
    overflow: hidden;
    box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
    transition: transform 0.3s, box-shadow 0.3s;
    border-left: 4px solid #667eea;
}

.oferta-admin-card:hover {
    transform: translateY(-2px);
    box-shadow: 0 5px 20px rgba(0, 0, 0, 0.1);
}

.oferta-admin-header {
    padding: 1.5rem;
    background: #f8f9fa;
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.comercio-info {
    display: flex;
    align-items: center;
    gap: 1rem;
}

.comercio-avatar {
    width: 50px;
    height: 50px;
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.2rem;
}

.comercio-details h4 {
    margin: 0 0 0.25rem 0;
    color: #2d3748;
    font-size: 1rem;
}

.comercio-details p {
    margin: 0;
    color: #718096;
    font-size: 0.85rem;
}

.puntos-badge-admin {
    background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
    color: white;
    padding: 0.5rem 1rem;
    border-radius: 20px;
    font-weight: 700;
    display: flex;
    align-items: center;
    gap: 0.3rem;
}

.oferta-admin-body {
    padding: 1.5rem;
}

.oferta-admin-body h3 {
    font-size: 1.2rem;
    color: #2d3748;
    margin-bottom: 0.75rem;
}

.oferta-descripcion {
    color: #718096;
    line-height: 1.5;
    margin: 0;
}

.oferta-admin-stats {
    padding: 1rem 1.5rem;
    background: #f8f9fa;
    display: flex;
    gap: 1.5rem;
    flex-wrap: wrap;
}

.stat-mini {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    font-size: 0.85rem;
    color: #4a5568;
}

.stat-mini i {
    color: #667eea;
}

.oferta-admin-actions {
    padding: 1rem 1.5rem;
    border-top: 1px solid #e2e8f0;
    display: flex;
    gap: 1rem;
}

.btn-edit {
    padding: 0.5rem 1rem;
    background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);
    color: white;
    text-decoration: none;
    border-radius: 6px;
    font-size: 0.9rem;
    font-weight: 600;
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
    transition: transform 0.2s;
}

.btn-edit:hover {
    transform: translateY(-1px);
    text-decoration: none;
    color: white;
}

.btn-delete {
    padding: 0.5rem 1rem;
    background: #e53e3e;
    color: white;
    border: none;
    border-radius: 6px;
    cursor: pointer;
    font-size: 0.9rem;
    font-weight: 600;
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
    transition: background 0.3s;
}

.btn-delete:hover {
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

@media (max-width: 768px) {
    .stats-grid {
        grid-template-columns: repeat(2, 1fr);
    }
    
    .ofertas-admin-grid {
        grid-template-columns: 1fr;
    }
    
    .oferta-admin-header {
        flex-direction: column;
        align-items: flex-start;
        gap: 1rem;
    }
    
    .oferta-admin-stats {
        flex-direction: column;
        gap: 0.75rem;
    }
}
</style>
</body>
</html>