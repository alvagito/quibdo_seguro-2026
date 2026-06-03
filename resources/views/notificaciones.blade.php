<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Notificaciones - Quibdó Seguro</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="{{ asset('assets/css/styles.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/pages.css') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        .notificaciones-main {
            background: #f5f7fa;
            min-height: 100vh;
        }
        .notif-stats {
            background: white;
            padding: 1rem 1.5rem;
            border-radius: 10px;
            margin-bottom: 2rem;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
        }

        .stat-badge {
            display: inline-flex;
            align-items: center;
            gap: 0.75rem;
            padding: 0.75rem 1.5rem;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            border-radius: 25px;
            font-weight: 600;
        }

        .notificaciones-lista {
            display: flex;
            flex-direction: column;
            gap: 1.5rem;
        }

        .notif-card {
            background: white;
            border-radius: 12px;
            padding: 1.5rem;
            display: flex;
            gap: 1.5rem;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
            transition: transform 0.3s, box-shadow 0.3s;
            border-left: 4px solid #e2e8f0;
        }

        .notif-card:hover {
            transform: translateX(5px);
            box-shadow: 0 5px 20px rgba(0, 0, 0, 0.1);
        }

        .notif-no-leida {
            background: #f0f8ff;
            border-left-color: #667eea;
        }

        .notif-icon {
            width: 50px;
            height: 50px;
            min-width: 50px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 1.5rem;
        }

        .notif-content {
            flex: 1;
        }

        .notif-header-modern {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 0.75rem;
        }

        .notif-header-modern h3 {
            font-size: 1.1rem;
            color: #2d3748;
            margin: 0;
        }

        .notif-mensaje {
            color: #4a5568;
            line-height: 1.6;
            margin-bottom: 1rem;
        }

        .notif-footer-modern {
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
            gap: 1rem;
        }

        .notif-tiempo {
            color: #718096;
            font-size: 0.85rem;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .btn-marcar-leida {
            padding: 0.5rem 1rem;
            background: #f8f9fa;
            color: #667eea;
            border: 2px solid #667eea;
            border-radius: 6px;
            font-size: 0.85rem;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .btn-marcar-leida:hover {
            background: #667eea;
            color: white;
        }

        @media (max-width: 768px) {
            .notif-card {
                flex-direction: column;
            }

            .notif-footer-modern {
                flex-direction: column;
                align-items: flex-start;
            }

            .btn-marcar-leida {
                width: 100%;
                justify-content: center;
            }
        }

        .notificacion {
            background: white;
            padding: 15px;
            margin: 10px 0;
            border-radius: 8px;
            border-left: 4px solid #4CAF50;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }
        .notificacion.no-leida {
            background: #f0f8ff;
            border-left-color: #2196F3;
        }
        .notificacion-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 8px;
        }
        .notificacion-titulo {
            font-weight: bold;
            font-size: 16px;
        }
        .notificacion-fecha {
            color: #666;
            font-size: 12px;
        }
        .badge {
            background: #2196F3;
            color: white;
            padding: 2px 8px;
            border-radius: 12px;
            font-size: 11px;
        }
    </style>
</head>
<body>
@include('components.header')

<main class="notificaciones-main">
    <!-- Hero Section -->
    <div class="page-hero">
        <h1><i class="fas fa-bell"></i> Notificaciones</h1>
        <p>Mantente al día con todas tus actualizaciones</p>
    </div>

    <div class="modern-container">
        @php
            $noLeidas = $notificaciones->where('leida', false)->count();
        @endphp

        @if($noLeidas > 0)
            <div class="notif-stats">
                <div class="stat-badge">
                    <i class="fas fa-envelope"></i>
                    <span>{{ $noLeidas }} notificación(es) sin leer</span>
                </div>
            </div>
        @endif

        @if($notificaciones->isEmpty())
            <div class="empty-state-modern">
                <i class="fas fa-bell-slash"></i>
                <h3>No tienes notificaciones</h3>
                <p>Cuando haya novedades, te avisaremos aquí</p>
            </div>
        @else
            <div class="notificaciones-lista">
                @foreach($notificaciones as $notif)
                    <div class="notif-card {{ !$notif->leida ? 'notif-no-leida' : '' }}">
                        <div class="notif-icon">
                            @switch($notif->tipo)
                                @case('validacion_reporte')
                                    <i class="fas fa-check-circle"></i>
                                    @break
                                @case('nuevo_reporte')
                                    <i class="fas fa-exclamation-triangle"></i>
                                    @break
                                @case('canje')
                                    <i class="fas fa-gift"></i>
                                    @break
                                @default
                                    <i class="fas fa-bell"></i>
                            @endswitch
                        </div>
                        <div class="notif-content">
                            <div class="notif-header-modern">
                                <h3>{{ $notif->titulo }}</h3>
                                @if(!$notif->leida)
                                    <span class="badge-modern badge-info">NUEVA</span>
                                @endif
                            </div>
                            <p class="notif-mensaje">{{ $notif->mensaje }}</p>
                            <div class="notif-footer-modern">
                                <span class="notif-tiempo">
                                    <i class="fas fa-clock"></i>
                                    {{ $notif->created_at->diffForHumans() }}
                                </span>
                                @if(!$notif->leida)
                                    <form action="{{ url('/notificaciones/'.$notif->_id.'/leer') }}" method="POST" style="display: inline;">
                                        @csrf
                                        <button type="submit" class="btn-marcar-leida">
                                            <i class="fas fa-check"></i> Marcar como leída
                                        </button>
                                    </form>
                                @endif
                            </div>
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
</body>
</html>
