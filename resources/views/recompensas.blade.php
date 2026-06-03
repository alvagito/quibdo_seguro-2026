<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Recompensas - Quibdó Seguro</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="{{ asset('assets/css/styles.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/pages.css') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body>
@include('components.header')

<main class="recompensas-main">
    <!-- Hero Section -->
    <div class="page-hero">
        <h1><i class="fas fa-gift"></i> Recompensas y Ofertas</h1>
        <p>Canjea tus puntos por increíbles recompensas</p>
    </div>

    <div class="modern-container">
        <!-- Tarjeta de Puntos -->
        <div class="puntos-banner">
            <div class="puntos-banner-content">
                <div class="puntos-icon-large">
                    <i class="fas fa-trophy"></i>
                </div>
                <div class="puntos-info-large">
                    <span class="puntos-label-large">Tus Puntos Disponibles</span>
                    <span class="puntos-numero-large">{{ Auth::user()->puntos ?? 0 }}</span>
                    <span class="puntos-sublabel">¡Sigue reportando para ganar más!</span>
                </div>
            </div>
        </div>

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
        @if(session('warning'))
            <div class="alert-modern alert-modern-warning">
                <i class="fas fa-exclamation-triangle"></i>
                <span>{{ session('warning') }}</span>
            </div>
        @endif

        <!-- Ofertas de Comercios -->
        @php
            $ofertas = \App\Models\Oferta::with('comercio')->get();
        @endphp

        @if($ofertas->count() > 0)
            <div class="section-header">
                <h2><i class="fas fa-store"></i> Ofertas de Comercios Aliados</h2>
                <p>Apoya a los comercios locales canjeando tus puntos</p>
            </div>

            <div class="ofertas-grid">
                @foreach($ofertas as $oferta)
                    <div class="oferta-card">
                        <div class="oferta-header">
                            <div class="comercio-badge">
                                <i class="fas fa-store"></i>
                                {{ $oferta->comercio->nombre }}
                            </div>
                            <div class="puntos-badge">
                                <i class="fas fa-star"></i>
                                {{ $oferta->puntos }}
                            </div>
                        </div>
                        <div class="oferta-body">
                            <h3>{{ $oferta->titulo }}</h3>
                            <p>{{ $oferta->descripcion }}</p>
                        </div>
                        <div class="oferta-footer">
                            @if(Auth::user()->puntos >= $oferta->puntos)
                                <form action="{{ url('/recompensas/canjear/'.$oferta->_id) }}" method="POST" style="width: 100%;">
                                    @csrf
                                    <button type="submit" class="btn-canjear">
                                        <i class="fas fa-gift"></i> Canjear Ahora
                                    </button>
                                </form>
                            @else
                                <div class="puntos-insuficientes">
                                    <i class="fas fa-lock"></i>
                                    Te faltan {{ $oferta->puntos - Auth::user()->puntos }} puntos
                                </div>
                            @endif
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <div class="empty-state-modern">
                <i class="fas fa-store-slash"></i>
                <h3>No hay ofertas disponibles</h3>
                <p>Pronto habrá nuevas ofertas de comercios aliados</p>
            </div>
        @endif

        <!-- Recompensas del Sistema -->
        @php
            $recompensas = \App\Models\Recompensa::all();
        @endphp
        
        @if($recompensas->count() > 0)
            <div class="section-header" style="margin-top: 3rem;">
                <h2><i class="fas fa-award"></i> Recompensas del Sistema</h2>
                <p>Recompensas especiales por tu participación</p>
            </div>

            <div class="recompensas-sistema-grid">
                @foreach($recompensas as $recompensa)
                    <div class="recompensa-card">
                        <div class="recompensa-header">
                            <div class="recompensa-icon">
                                <i class="fas fa-award"></i>
                            </div>
                            <div class="puntos-badge-sistema">
                                <i class="fas fa-star"></i>
                                {{ $recompensa->puntos }}
                            </div>
                        </div>
                        <div class="recompensa-body">
                            <h3>{{ $recompensa->nombre }}</h3>
                        </div>
                        <div class="recompensa-footer">
                            @if(Auth::user()->puntos >= $recompensa->puntos)
                                <form action="{{ url('/recompensas/canjear/'.$recompensa->_id) }}" method="POST" style="width: 100%;">
                                    @csrf
                                    <button type="submit" class="btn-canjear-sistema">
                                        <i class="fas fa-gift"></i> Canjear Ahora
                                    </button>
                                </form>
                            @else
                                <div class="puntos-insuficientes">
                                    <i class="fas fa-lock"></i>
                                    Te faltan {{ $recompensa->puntos - Auth::user()->puntos }} puntos
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

<style>
.recompensas-main {
    background: #f5f7fa;
    min-height: 100vh;
}

.puntos-banner {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    border-radius: 15px;
    padding: 2rem;
    margin-bottom: 2rem;
    box-shadow: 0 5px 20px rgba(102, 126, 234, 0.3);
}

.puntos-banner-content {
    display: flex;
    align-items: center;
    gap: 2rem;
    color: white;
}

.puntos-icon-large {
    width: 100px;
    height: 100px;
    background: rgba(255, 255, 255, 0.2);
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 3rem;
}

.puntos-info-large {
    display: flex;
    flex-direction: column;
}

.puntos-label-large {
    font-size: 1rem;
    opacity: 0.9;
}

.puntos-numero-large {
    font-size: 4rem;
    font-weight: 700;
    line-height: 1;
    margin: 0.5rem 0;
}

.puntos-sublabel {
    font-size: 0.9rem;
    opacity: 0.8;
}

.section-header {
    margin-bottom: 2rem;
}

.section-header h2 {
    font-size: 1.8rem;
    color: #2d3748;
    margin-bottom: 0.5rem;
    display: flex;
    align-items: center;
    gap: 0.5rem;
}

.section-header h2 i {
    color: #667eea;
}

.section-header p {
    color: #718096;
    font-size: 1rem;
}

.ofertas-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(320px, 1fr));
    gap: 2rem;
    margin-bottom: 3rem;
}

.oferta-card {
    background: white;
    border-radius: 15px;
    overflow: hidden;
    box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
    transition: transform 0.3s, box-shadow 0.3s;
}

.oferta-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 5px 20px rgba(0, 0, 0, 0.1);
}

.oferta-header {
    background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
    padding: 1.5rem;
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.comercio-badge {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    color: #4a5568;
    font-weight: 600;
    font-size: 0.9rem;
}

.comercio-badge i {
    color: #667eea;
}

.puntos-badge {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    padding: 0.5rem 1rem;
    border-radius: 20px;
    font-weight: 700;
    display: flex;
    align-items: center;
    gap: 0.3rem;
}

.oferta-body {
    padding: 1.5rem;
}

.oferta-body h3 {
    font-size: 1.3rem;
    color: #2d3748;
    margin-bottom: 0.75rem;
}

.oferta-body p {
    color: #718096;
    line-height: 1.6;
}

.oferta-footer {
    padding: 1.5rem;
    border-top: 1px solid #e2e8f0;
}

.btn-canjear {
    width: 100%;
    padding: 0.75rem;
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    border: none;
    border-radius: 8px;
    font-weight: 600;
    font-size: 1rem;
    cursor: pointer;
    transition: transform 0.2s, box-shadow 0.2s;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 0.5rem;
}

.btn-canjear:hover {
    transform: translateY(-2px);
    box-shadow: 0 5px 15px rgba(102, 126, 234, 0.3);
}

.puntos-insuficientes {
    text-align: center;
    padding: 0.75rem;
    background: #f8f9fa;
    border-radius: 8px;
    color: #718096;
    font-size: 0.9rem;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 0.5rem;
}

.recompensas-sistema-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
    gap: 1.5rem;
    margin-bottom: 3rem;
}

.recompensa-card {
    background: white;
    border-radius: 15px;
    overflow: hidden;
    box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
    transition: transform 0.3s, box-shadow 0.3s;
    border-left: 4px solid #f093fb;
}

.recompensa-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 5px 20px rgba(0, 0, 0, 0.1);
}

.recompensa-header {
    background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
    padding: 1.5rem;
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.recompensa-icon {
    width: 50px;
    height: 50px;
    background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
    color: white;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.3rem;
}

.puntos-badge-sistema {
    background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
    color: white;
    padding: 0.5rem 1rem;
    border-radius: 20px;
    font-weight: 700;
    display: flex;
    align-items: center;
    gap: 0.3rem;
}

.recompensa-body {
    padding: 1.5rem;
}

.recompensa-body h3 {
    font-size: 1.2rem;
    color: #2d3748;
    margin: 0;
    text-align: center;
}

.recompensa-footer {
    padding: 1.5rem;
    border-top: 1px solid #e2e8f0;
}

.btn-canjear-sistema {
    width: 100%;
    padding: 0.75rem;
    background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
    color: white;
    border: none;
    border-radius: 8px;
    font-weight: 600;
    font-size: 1rem;
    cursor: pointer;
    transition: transform 0.2s, box-shadow 0.2s;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 0.5rem;
}

.btn-canjear-sistema:hover {
    transform: translateY(-2px);
    box-shadow: 0 5px 15px rgba(240, 147, 251, 0.3);
}

@media (max-width: 768px) {
    .puntos-banner-content {
        flex-direction: column;
        text-align: center;
    }
    
    .puntos-numero-large {
        font-size: 3rem;
    }
    
    .ofertas-grid {
        grid-template-columns: 1fr;
    }
    
    .recompensas-sistema-grid {
        grid-template-columns: 1fr;
    }
}
</style>
</body>
</html>
