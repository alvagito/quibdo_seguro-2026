<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Mis Canjes - Quibdó Seguro</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="{{ asset('assets/css/styles.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/pages.css') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        .canjes-main {
            background: #f5f7fa;
            min-height: 100vh;
        }
        .canjes-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(400px, 1fr));
            gap: 2rem;
        }

        .canje-card-modern {
            border-left: 4px solid #667eea;
        }

        .canje-card-header {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            margin-bottom: 1.5rem;
            padding-bottom: 1rem;
            border-bottom: 2px solid #f0f0f0;
        }

        .canje-info h3 {
            font-size: 1.3rem;
            color: #2d3748;
            margin-bottom: 0.5rem;
        }

        .comercio-name {
            color: #718096;
            font-size: 0.9rem;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .comercio-name i {
            color: #667eea;
        }

        .canje-card-body {
            padding: 0;
        }

        .canje-descripcion {
            color: #4a5568;
            line-height: 1.6;
            margin-bottom: 1.5rem;
        }

        .codigo-qr-modern {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            padding: 1.5rem;
            border-radius: 10px;
            text-align: center;
            margin-bottom: 1.5rem;
            position: relative;
            overflow: hidden;
        }

        .qr-label {
            color: rgba(255, 255, 255, 0.9);
            font-size: 0.9rem;
            margin-bottom: 0.5rem;
        }

        .qr-code {
            font-size: 2.5rem;
            font-weight: 700;
            color: white;
            letter-spacing: 4px;
            font-family: 'Courier New', monospace;
        }

        .qr-icon {
            position: absolute;
            top: 10px;
            right: 10px;
            font-size: 3rem;
            color: rgba(255, 255, 255, 0.1);
        }

        .canje-detalles {
            display: flex;
            flex-direction: column;
            gap: 0.75rem;
        }

        .detalle-item {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            padding: 0.75rem;
            background: #f8f9fa;
            border-radius: 6px;
            font-size: 0.9rem;
        }

        .detalle-item i {
            color: #667eea;
            width: 20px;
        }

        @media (max-width: 768px) {
            .canjes-grid {
                grid-template-columns: 1fr;
            }

            .qr-code {
                font-size: 2rem;
            }
        }

        .filtros-canjes {
            display: flex;
            gap: 1rem;
            margin-bottom: 2rem;
            flex-wrap: wrap;
        }

        .filtro-btn {
            padding: 0.75rem 1.5rem;
            background: white;
            border: 2px solid #e2e8f0;
            border-radius: 25px;
            cursor: pointer;
            transition: all 0.3s;
            display: flex;
            align-items: center;
            gap: 0.5rem;
            font-weight: 600;
            color: #4a5568;
        }

        .filtro-btn:hover {
            border-color: #667eea;
            color: #667eea;
        }

        .filtro-btn.active {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border-color: #667eea;
            color: white;
        }

        .canje-card {
            background: white;
            padding: 20px;
            margin: 15px 0;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }
        .canje-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 15px;
        }
        .codigo-qr {
            background: #f5f5f5;
            padding: 15px;
            border-radius: 8px;
            text-align: center;
            font-size: 24px;
            font-weight: bold;
            letter-spacing: 2px;
            margin: 10px 0;
        }
        .estado-badge {
            padding: 5px 15px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: bold;
        }
        .estado-pendiente {
            background: #FFF3CD;
            color: #856404;
        }
        .estado-validado {
            background: #D4EDDA;
            color: #155724;
        }
    </style>
</head>
<body>
@include('components.header')

<main class="canjes-main">
    <!-- Hero Section -->
    <div class="page-hero">
        <h1><i class="fas fa-ticket-alt"></i> Mis Canjes</h1>
        <p>Historial de todas tus recompensas canjeadas</p>
    </div>

    <div class="modern-container">

        <!-- Filtros -->
        <div class="filtros-canjes">
            <button class="filtro-btn active" onclick="filtrarCanjes('todos')">
                <i class="fas fa-list"></i> Todos ({{ $canjes->count() }})
            </button>
            <button class="filtro-btn" onclick="filtrarCanjes('pendiente')">
                <i class="fas fa-hourglass-half"></i> Pendientes ({{ $canjes->where('estado', 'pendiente')->count() }})
            </button>
            <button class="filtro-btn" onclick="filtrarCanjes('validado')">
                <i class="fas fa-check-circle"></i> Validados ({{ $canjes->where('estado', 'validado')->count() }})
            </button>
        </div>

        @if($canjes->isEmpty())
            <div class="empty-state-modern">
                <i class="fas fa-ticket-alt"></i>
                <h3>No has realizado canjes</h3>
                <p>Cuando canjees recompensas, aparecerán aquí</p>
                <a href="{{ url('/recompensas') }}" class="btn-modern">
                    <i class="fas fa-gift"></i> Ver Recompensas
                </a>
            </div>
        @else
            <div class="canjes-grid">
                @foreach($canjes as $canje)
                    <div class="modern-card canje-card-modern" data-estado="{{ $canje->estado }}">
                        <div class="canje-card-header">
                            <div class="canje-info">
                                <h3>{{ $canje->oferta->titulo ?? 'Recompensa del Sistema' }}</h3>
                                @if($canje->comercio)
                                    <p class="comercio-name">
                                        <i class="fas fa-store"></i> {{ $canje->comercio->nombre }}
                                    </p>
                                @else
                                    <p class="comercio-name">
                                        <i class="fas fa-award"></i> Recompensa del Sistema
                                    </p>
                                @endif
                            </div>
                            <span class="badge-modern {{ $canje->estado == 'pendiente' ? 'badge-warning' : 'badge-success' }}">
                                <i class="fas {{ $canje->estado == 'pendiente' ? 'fa-hourglass-half' : 'fa-check-circle' }}"></i>
                                {{ $canje->estado == 'pendiente' ? 'Pendiente' : 'Validado' }}
                            </span>
                        </div>

                        <div class="canje-card-body">
                            @if($canje->oferta)
                                <p class="canje-descripcion">{{ $canje->oferta->descripcion }}</p>
                            @endif
                            
                            @if($canje->codigo_qr)
                                <div class="codigo-qr-modern">
                                    <div class="qr-label">Código de Canje</div>
                                    <div class="qr-code">{{ $canje->codigo_qr }}</div>
                                    <div class="qr-icon"><i class="fas fa-qrcode"></i></div>
                                </div>
                            @endif

                            <div class="canje-detalles">
                                <div class="detalle-item">
                                    <i class="fas fa-star"></i>
                                    <span><strong>Puntos:</strong> {{ $canje->puntos_canjeados }}</span>
                                </div>
                                <div class="detalle-item">
                                    <i class="fas fa-calendar"></i>
                                    <span><strong>Canjeado:</strong> {{ $canje->fecha_canje->format('d/m/Y H:i') }}</span>
                                </div>
                                @if($canje->estado == 'validado' && $canje->fecha_validacion)
                                    <div class="detalle-item">
                                        <i class="fas fa-check"></i>
                                        <span><strong>Validado:</strong> {{ $canje->fecha_validacion->format('d/m/Y H:i') }}</span>
                                    </div>
                                @endif
                            </div>

                            @if($canje->estado == 'pendiente' && $canje->codigo_qr)
                                <div class="alert-modern alert-modern-info" style="margin-top: 1rem;">
                                    <i class="fas fa-info-circle"></i>
                                    <span>Muestra este código al comercio para validar tu canje</span>
                                </div>
                            @elseif($canje->estado == 'validado')
                                <div class="alert-modern alert-modern-success" style="margin-top: 1rem;">
                                    <i class="fas fa-check-circle"></i>
                                    <span>¡Canje completado exitosamente!</span>
                                </div>
                            @endif
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    </div>
</main>

<footer>
    <p>© {{ date('Y') }} Quibdó Seguro - Todos los derechos reservados.</p>
</footer>
<script>
function filtrarCanjes(estado) {
    // Actualizar botones activos
    document.querySelectorAll('.filtro-btn').forEach(btn => {
        btn.classList.remove('active');
    });
    event.target.classList.add('active');
    
    // Filtrar tarjetas
    document.querySelectorAll('.canje-card-modern').forEach(card => {
        if (estado === 'todos') {
            card.style.display = 'block';
        } else {
            const cardEstado = card.getAttribute('data-estado');
            card.style.display = cardEstado === estado ? 'block' : 'none';
        }
    });
}
</script>
</body>
</html>
