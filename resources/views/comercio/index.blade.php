<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Panel Comercio - Quibdó Seguro</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="{{ asset('assets/css/styles.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/pages.css') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body>
@include('components.header')

<main class="comercio-main">
    <!-- Hero Section -->
    <div class="page-hero">
        <h1><i class="fas fa-store"></i> Panel del Comercio</h1>
        <p>Bienvenido {{ $usuario->nombre ?? 'Comercio Aliado' }}</p>
    </div>

    <div class="modern-container">
        <!-- Estadísticas Rápidas -->
        @php
            $totalOfertas = $usuario->ofertas()->count();
            $canjesValidados = \App\Models\Canje::where('id_comercio', $usuario->_id)->where('estado', 'validado')->count();
            $canjesPendientes = \App\Models\Canje::where('id_comercio', $usuario->_id)->where('estado', 'pendiente')->count();
            $puntosRedimidos = \App\Models\Canje::where('id_comercio', $usuario->_id)->where('estado', 'validado')->sum('puntos_canjeados');
        @endphp

        <div class="stats-grid">
            <div class="stat-card stat-primary">
                <div class="stat-icon">
                    <i class="fas fa-tags"></i>
                </div>
                <div class="stat-content">
                    <div class="stat-number">{{ $totalOfertas }}</div>
                    <div class="stat-label">Ofertas Activas</div>
                </div>
            </div>

            <div class="stat-card stat-success">
                <div class="stat-icon">
                    <i class="fas fa-check-circle"></i>
                </div>
                <div class="stat-content">
                    <div class="stat-number">{{ $canjesValidados }}</div>
                    <div class="stat-label">Canjes Validados</div>
                </div>
            </div>

            <div class="stat-card stat-warning">
                <div class="stat-icon">
                    <i class="fas fa-hourglass-half"></i>
                </div>
                <div class="stat-content">
                    <div class="stat-number">{{ $canjesPendientes }}</div>
                    <div class="stat-label">Canjes Pendientes</div>
                </div>
            </div>

            <div class="stat-card stat-info">
                <div class="stat-icon">
                    <i class="fas fa-star"></i>
                </div>
                <div class="stat-content">
                    <div class="stat-number">{{ $puntosRedimidos }}</div>
                    <div class="stat-label">Puntos Redimidos</div>
                </div>
            </div>
        </div>

        <!-- Acciones Principales -->
        <div class="actions-grid">
            <a href="{{ url('/comercio/ofertas') }}" class="action-card action-primary">
                <div class="action-icon">
                    <i class="fas fa-plus-circle"></i>
                </div>
                <div class="action-content">
                    <h3>Gestionar Ofertas</h3>
                    <p>Crea, edita y administra tus ofertas</p>
                </div>
                <div class="action-arrow">
                    <i class="fas fa-chevron-right"></i>
                </div>
            </a>

            <a href="{{ url('/comercio/estadisticas') }}" class="action-card action-success">
                <div class="action-icon">
                    <i class="fas fa-chart-bar"></i>
                </div>
                <div class="action-content">
                    <h3>Estadísticas</h3>
                    <p>Ve el rendimiento de tus ofertas</p>
                </div>
                <div class="action-arrow">
                    <i class="fas fa-chevron-right"></i>
                </div>
            </a>

            <a href="{{ url('/comercio/estadisticas') }}" class="action-card action-warning">
                <div class="action-icon">
                    <i class="fas fa-qrcode"></i>
                </div>
                <div class="action-content">
                    <h3>Validar Canjes</h3>
                    <p>Escanea códigos QR de clientes</p>
                </div>
                <div class="action-arrow">
                    <i class="fas fa-chevron-right"></i>
                </div>
            </a>
        </div>

        <!-- Información del Comercio -->
        <div class="modern-card">
            <div class="card-header">
                <h2><i class="fas fa-info-circle"></i> Información del Comercio</h2>
            </div>
            <div class="comercio-info">
                <div class="info-item">
                    <i class="fas fa-store"></i>
                    <div>
                        <strong>Nombre:</strong>
                        <span>{{ $usuario->nombre }}</span>
                    </div>
                </div>
                <div class="info-item">
                    <i class="fas fa-envelope"></i>
                    <div>
                        <strong>Email:</strong>
                        <span>{{ $usuario->email }}</span>
                    </div>
                </div>
                <div class="info-item">
                    <i class="fas fa-calendar"></i>
                    <div>
                        <strong>Miembro desde:</strong>
                        <span>{{ $usuario->created_at ? $usuario->created_at->format('d/m/Y') : 'N/A' }}</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</main>

<footer>
    <p>© {{ date('Y') }} Quibdó Seguro - Todos los derechos reservados.</p>
</footer>

<style>
.comercio-main {
    background: #f5f7fa;
    min-height: 100vh;
}

.stats-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
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
    width: 60px;
    height: 60px;
    border-radius: 12px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.5rem;
    color: white;
}

.stat-primary .stat-icon { background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); }
.stat-success .stat-icon { background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%); }
.stat-warning .stat-icon { background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%); }
.stat-info .stat-icon { background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%); }

.stat-content {
    flex: 1;
}

.stat-number {
    font-size: 2rem;
    font-weight: 700;
    color: #2d3748;
    line-height: 1;
}

.stat-label {
    font-size: 0.9rem;
    color: #718096;
    margin-top: 0.25rem;
}

.actions-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
    gap: 1.5rem;
    margin-bottom: 2rem;
}

.action-card {
    background: white;
    padding: 1.5rem;
    border-radius: 12px;
    box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
    display: flex;
    align-items: center;
    gap: 1rem;
    text-decoration: none;
    color: inherit;
    transition: transform 0.3s, box-shadow 0.3s;
    border-left: 4px solid transparent;
}

.action-card:hover {
    transform: translateY(-2px);
    box-shadow: 0 5px 20px rgba(0, 0, 0, 0.1);
    text-decoration: none;
    color: inherit;
}

.action-primary { border-left-color: #667eea; }
.action-success { border-left-color: #48bb78; }
.action-warning { border-left-color: #ed8936; }

.action-icon {
    width: 50px;
    height: 50px;
    border-radius: 10px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.3rem;
    color: white;
}

.action-primary .action-icon { background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); }
.action-success .action-icon { background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%); }
.action-warning .action-icon { background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%); }

.action-content {
    flex: 1;
}

.action-content h3 {
    margin: 0 0 0.5rem 0;
    font-size: 1.1rem;
    color: #2d3748;
}

.action-content p {
    margin: 0;
    color: #718096;
    font-size: 0.9rem;
}

.action-arrow {
    color: #cbd5e0;
    font-size: 1.2rem;
}

.comercio-info {
    display: flex;
    flex-direction: column;
    gap: 1rem;
}

.info-item {
    display: flex;
    align-items: center;
    gap: 1rem;
    padding: 1rem;
    background: #f8f9fa;
    border-radius: 8px;
}

.info-item i {
    width: 40px;
    height: 40px;
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    border-radius: 8px;
    display: flex;
    align-items: center;
    justify-content: center;
}

.info-item div {
    display: flex;
    flex-direction: column;
    gap: 0.25rem;
}

.info-item strong {
    color: #4a5568;
    font-size: 0.9rem;
}

.info-item span {
    color: #2d3748;
    font-size: 1rem;
}

@media (max-width: 768px) {
    .stats-grid {
        grid-template-columns: repeat(2, 1fr);
    }
    
    .actions-grid {
        grid-template-columns: 1fr;
    }
    
    .stat-number {
        font-size: 1.5rem;
    }
}
</style>
</body>
</html>
