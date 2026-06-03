<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Estadísticas y Validación - Quibdó Seguro</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="{{ asset('assets/css/styles.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/pages.css') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body>
@include('components.header')

<main class="estadisticas-main">
    <!-- Hero Section -->
    <div class="page-hero">
        <h1><i class="fas fa-chart-bar"></i> Estadísticas y Validación</h1>
        <p>Monitorea el rendimiento de tu comercio y valida canjes</p>
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

        <!-- Estadísticas Principales -->
        <div class="stats-grid">
            <div class="stat-card stat-primary">
                <div class="stat-icon">
                    <i class="fas fa-tags"></i>
                </div>
                <div class="stat-content">
                    <div class="stat-number">{{ $totalOfertas }}</div>
                    <div class="stat-label">Ofertas Activas</div>
                    <div class="stat-description">Total de ofertas publicadas</div>
                </div>
            </div>

            <div class="stat-card stat-success">
                <div class="stat-icon">
                    <i class="fas fa-check-circle"></i>
                </div>
                <div class="stat-content">
                    <div class="stat-number">{{ $canjeadas }}</div>
                    <div class="stat-label">Canjes Validados</div>
                    <div class="stat-description">Ofertas utilizadas por clientes</div>
                </div>
            </div>

            <div class="stat-card stat-info">
                <div class="stat-icon">
                    <i class="fas fa-star"></i>
                </div>
                <div class="stat-content">
                    <div class="stat-number">{{ $puntosRedimidos }}</div>
                    <div class="stat-label">Puntos Redimidos</div>
                    <div class="stat-description">Total de puntos canjeados</div>
                </div>
            </div>

            <div class="stat-card stat-warning">
                <div class="stat-icon">
                    <i class="fas fa-hourglass-half"></i>
                </div>
                <div class="stat-content">
                    <div class="stat-number">{{ $canjesPendientes->count() }}</div>
                    <div class="stat-label">Canjes Pendientes</div>
                    <div class="stat-description">Esperando validación</div>
                </div>
            </div>
        </div>

        <!-- Validador de Códigos QR -->
        <div class="modern-card">
            <div class="card-header">
                <h2><i class="fas fa-qrcode"></i> Validar Código QR</h2>
                <p>Escanea o ingresa el código del cliente para validar su canje</p>
            </div>
            
            <div class="qr-validator">
                <form action="{{ url('/comercio/validar-canje') }}" method="POST" class="validator-form">
                    @csrf
                    <div class="form-group">
                        <label><i class="fas fa-keyboard"></i> Código de Canje</label>
                        <input type="text" name="codigo" placeholder="Ingresa el código QR del cliente" required class="form-control qr-input">
                    </div>
                    <button type="submit" class="btn-validate">
                        <i class="fas fa-check"></i> Validar Canje
                    </button>
                </form>
                
                <div class="qr-instructions">
                    <div class="instruction-item">
                        <i class="fas fa-mobile-alt"></i>
                        <span>Pide al cliente que muestre su código QR</span>
                    </div>
                    <div class="instruction-item">
                        <i class="fas fa-keyboard"></i>
                        <span>Ingresa el código en el campo de arriba</span>
                    </div>
                    <div class="instruction-item">
                        <i class="fas fa-check-circle"></i>
                        <span>Confirma la validación del canje</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Canjes Pendientes -->
        @if($canjesPendientes->count() > 0)
            <div class="modern-card">
                <div class="card-header">
                    <h2><i class="fas fa-clock"></i> Canjes Pendientes de Validación</h2>
                    <span class="badge-modern badge-warning">{{ $canjesPendientes->count() }} pendientes</span>
                </div>

                <div class="canjes-pendientes-grid">
                    @foreach($canjesPendientes as $canje)
                        <div class="canje-pendiente-card">
                            <div class="canje-header">
                                <div class="cliente-info">
                                    <h4>{{ $canje->usuario->nombre }}</h4>
                                    <p>{{ $canje->oferta->titulo }}</p>
                                </div>
                                <div class="puntos-info">
                                    <span class="puntos-badge">
                                        <i class="fas fa-star"></i> {{ $canje->puntos_canjeados }}
                                    </span>
                                </div>
                            </div>
                            
                            <div class="codigo-display">
                                <div class="codigo-label">Código QR:</div>
                                <div class="codigo-value">{{ $canje->codigo_qr }}</div>
                            </div>
                            
                            <div class="canje-actions">
                                <form action="{{ url('/comercio/validar-canje/'.$canje->codigo_qr) }}" method="POST">
                                    @csrf
                                    <button type="submit" class="btn-validate-small">
                                        <i class="fas fa-check"></i> Validar
                                    </button>
                                </form>
                                <div class="fecha-canje">
                                    <i class="fas fa-calendar"></i>
                                    {{ $canje->fecha_canje->format('d/m/Y H:i') }}
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        @endif

        <!-- Información adicional -->
        <div class="info-card">
            <i class="fas fa-info-circle"></i>
            <div>
                <strong>Sobre la Validación de Canjes</strong>
                <p>Cuando un cliente canjea una oferta, recibe un código QR único. Para completar el proceso, debes validar este código cuando el cliente visite tu comercio. Una vez validado, el canje se marca como completado.</p>
            </div>
        </div>
    </div>
</main>

<footer>
    <p>© {{ date('Y') }} Quibdó Seguro - Todos los derechos reservados.</p>
</footer>

<style>
.estadisticas-main {
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
    align-items: flex-start;
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
.stat-info .stat-icon { background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%); }
.stat-warning .stat-icon { background: linear-gradient(135deg, #ffecd2 0%, #fcb69f 100%); color: #8b4513 !important; }

.stat-content {
    flex: 1;
}

.stat-number {
    font-size: 2.2rem;
    font-weight: 700;
    color: #2d3748;
    line-height: 1;
}

.stat-label {
    font-size: 1rem;
    color: #4a5568;
    margin: 0.25rem 0;
    font-weight: 600;
}

.stat-description {
    font-size: 0.85rem;
    color: #718096;
}

.qr-validator {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 2rem;
    align-items: start;
}

.validator-form {
    display: flex;
    flex-direction: column;
    gap: 1rem;
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

.qr-input {
    padding: 1rem;
    border: 2px solid #e2e8f0;
    border-radius: 8px;
    font-size: 1.1rem;
    font-family: 'Courier New', monospace;
    text-align: center;
    letter-spacing: 2px;
    transition: border-color 0.3s;
}

.qr-input:focus {
    outline: none;
    border-color: #667eea;
}

.btn-validate {
    padding: 1rem 2rem;
    background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);
    color: white;
    border: none;
    border-radius: 8px;
    font-weight: 600;
    font-size: 1.1rem;
    cursor: pointer;
    transition: transform 0.2s, box-shadow 0.2s;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 0.5rem;
}

.btn-validate:hover {
    transform: translateY(-2px);
    box-shadow: 0 5px 15px rgba(79, 172, 254, 0.3);
}

.qr-instructions {
    display: flex;
    flex-direction: column;
    gap: 1rem;
}

.instruction-item {
    display: flex;
    align-items: center;
    gap: 1rem;
    padding: 1rem;
    background: #f8f9fa;
    border-radius: 8px;
}

.instruction-item i {
    width: 40px;
    height: 40px;
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    border-radius: 8px;
    display: flex;
    align-items: center;
    justify-content: center;
}

.canjes-pendientes-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(350px, 1fr));
    gap: 1.5rem;
}

.canje-pendiente-card {
    background: white;
    border: 2px solid #fed7d7;
    border-radius: 12px;
    padding: 1.5rem;
    transition: transform 0.3s, box-shadow 0.3s;
}

.canje-pendiente-card:hover {
    transform: translateY(-2px);
    box-shadow: 0 5px 20px rgba(0, 0, 0, 0.1);
}

.canje-header {
    display: flex;
    justify-content: space-between;
    align-items: flex-start;
    margin-bottom: 1rem;
}

.cliente-info h4 {
    margin: 0 0 0.25rem 0;
    color: #2d3748;
    font-size: 1.1rem;
}

.cliente-info p {
    margin: 0;
    color: #718096;
    font-size: 0.9rem;
}

.puntos-badge {
    background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
    color: white;
    padding: 0.5rem 1rem;
    border-radius: 20px;
    font-weight: 600;
    display: flex;
    align-items: center;
    gap: 0.3rem;
}

.codigo-display {
    background: #f8f9fa;
    padding: 1rem;
    border-radius: 8px;
    margin-bottom: 1rem;
    text-align: center;
}

.codigo-label {
    font-size: 0.85rem;
    color: #718096;
    margin-bottom: 0.5rem;
}

.codigo-value {
    font-size: 1.5rem;
    font-weight: 700;
    color: #2d3748;
    font-family: 'Courier New', monospace;
    letter-spacing: 3px;
}

.canje-actions {
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.btn-validate-small {
    padding: 0.5rem 1rem;
    background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);
    color: white;
    border: none;
    border-radius: 6px;
    cursor: pointer;
    font-weight: 600;
    display: flex;
    align-items: center;
    gap: 0.5rem;
    transition: transform 0.2s;
}

.btn-validate-small:hover {
    transform: translateY(-1px);
}

.fecha-canje {
    color: #718096;
    font-size: 0.85rem;
    display: flex;
    align-items: center;
    gap: 0.5rem;
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
    
    .qr-validator {
        grid-template-columns: 1fr;
    }
    
    .canjes-pendientes-grid {
        grid-template-columns: 1fr;
    }
    
    .stat-number {
        font-size: 1.8rem;
    }
}
</style>
</body>
</html>
