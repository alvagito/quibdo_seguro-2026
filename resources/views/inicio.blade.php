<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Quibdó Seguro - Plataforma Comunitaria</title>
    <link rel="stylesheet" href="{{ asset('assets/css/styles.css') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <!-- Leaflet CSS -->
    <link rel="stylesheet" href="https://unpkg.com/leaflet/dist/leaflet.css"/>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            overflow-x: hidden;
        }

        /* Hero Section Mejorado */
        .hero-landing {
            background: url('{{ asset("assets/images/quibdo-banner.jpg") }}');
            background-size: cover;
            background-position: center;
            background-attachment: fixed;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            position: relative;
            color: white;
        }

        .hero-landing::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: linear-gradient(135deg, rgba(102, 126, 234, 0.85) 0%, rgba(118, 75, 162, 0.85) 100%);
        }

        .hero-content-landing {
            position: relative;
            z-index: 2;
            text-align: center;
            max-width: 900px;
            padding: 2rem;
            animation: fadeInUp 1s ease;
        }

        .logo-landing {
            font-size: 4rem;
            font-weight: 700;
            margin-bottom: 1rem;
            text-shadow: 3px 3px 10px rgba(0, 0, 0, 0.3);
            animation: fadeInDown 1s ease;
        }

        .tagline {
            font-size: 1.5rem;
            margin-bottom: 3rem;
            opacity: 0.95;
            line-height: 1.8;
            text-shadow: 2px 2px 8px rgba(0, 0, 0, 0.3);
            animation: fadeIn 1.5s ease;
        }

        .cta-buttons-landing {
            display: flex;
            gap: 1.5rem;
            justify-content: center;
            flex-wrap: wrap;
            animation: fadeInUp 1.5s ease;
        }

        .btn-landing {
            padding: 1rem 2.5rem;
            font-size: 1.1rem;
            font-weight: 600;
            border-radius: 50px;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 0.75rem;
            transition: all 0.3s ease;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.2);
        }

        .btn-primary-landing {
            background: white;
            color: #667eea;
        }

        .btn-primary-landing:hover {
            transform: translateY(-3px);
            box-shadow: 0 8px 25px rgba(255, 255, 255, 0.3);
        }

        .btn-secondary-landing {
            background: rgba(255, 255, 255, 0.2);
            color: white;
            border: 2px solid white;
            backdrop-filter: blur(10px);
        }

        .btn-secondary-landing:hover {
            background: white;
            color: #667eea;
            transform: translateY(-3px);
        }

        /* Mapa Público Section */
        .mapa-publico-section {
            background: #f5f7fa;
            padding: 4rem 2rem;
        }

        .mapa-publico-container {
            max-width: 1200px;
            margin: 0 auto;
        }

        .mapa-publico-header {
            text-align: center;
            margin-bottom: 3rem;
        }

        .mapa-publico-header h2 {
            font-size: 2.5rem;
            color: #2d3748;
            margin-bottom: 1rem;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 1rem;
        }

        .mapa-publico-header h2 i {
            color: #667eea;
        }

        .mapa-publico-header p {
            font-size: 1.2rem;
            color: #718096;
        }

        .mapa-publico-wrapper {
            background: white;
            border-radius: 15px;
            padding: 1.5rem;
            box-shadow: 0 5px 20px rgba(0, 0, 0, 0.1);
        }

        #mapaPublico {
            height: 500px;
            width: 100%;
            border-radius: 10px;
        }

        /* Reportes Section */
        .reportes-section {
            background: white;
            padding: 4rem 2rem;
        }

        .reportes-container {
            max-width: 1200px;
            margin: 0 auto;
        }

        .reportes-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(350px, 1fr));
            gap: 2rem;
            margin-bottom: 3rem;
        }

        .reporte-card {
            background: #f8f9fa;
            border-radius: 12px;
            padding: 1.5rem;
            border: 1px solid #e2e8f0;
            transition: transform 0.3s, box-shadow 0.3s;
        }

        .reporte-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
        }

        .reporte-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 1rem;
        }

        .reporte-tipo {
            padding: 0.5rem 1rem;
            border-radius: 20px;
            font-size: 0.85rem;
            font-weight: 600;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .tipo-1 {
            background: #fff5f5;
            color: #c53030;
        }

        .tipo-2 {
            background: #fffaf0;
            color: #c05621;
        }

        .tipo-3 {
            background: #fef5e7;
            color: #d68910;
        }

        .tipo-4 {
            background: #f0f4f8;
            color: #4a5568;
        }

        .reporte-fecha {
            font-size: 0.85rem;
            color: #718096;
        }

        .reporte-descripcion {
            color: #4a5568;
            line-height: 1.6;
            margin-bottom: 1rem;
        }

        .reporte-ubicacion {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            color: #718096;
            font-size: 0.9rem;
            margin-bottom: 1rem;
        }

        .reporte-ubicacion i {
            color: #667eea;
        }

        .reporte-footer {
            border-top: 1px solid #e2e8f0;
            padding-top: 1rem;
        }

        .reporte-estado {
            padding: 0.4rem 0.8rem;
            border-radius: 15px;
            font-size: 0.8rem;
            font-weight: 600;
            display: inline-flex;
            align-items: center;
            gap: 0.4rem;
        }

        .reporte-estado {
            background: #d4edda;
            color: #155724;
        }

        .no-reportes {
            grid-column: 1 / -1;
            text-align: center;
            padding: 3rem;
            color: #718096;
        }

        .no-reportes i {
            font-size: 3rem;
            margin-bottom: 1rem;
            opacity: 0.5;
        }

        .reportes-cta {
            text-align: center;
        }

        /* Stats Section */
        .stats-section {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            padding: 4rem 2rem;
            color: white;
        }

        .stats-container {
            max-width: 1200px;
            margin: 0 auto;
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 3rem;
        }

        .stat-item-landing {
            text-align: center;
            animation: fadeInUp 1s ease;
        }

        .stat-icon-landing {
            font-size: 3rem;
            margin-bottom: 1rem;
            opacity: 0.9;
        }

        .stat-number-landing {
            font-size: 3rem;
            font-weight: 700;
            display: block;
            margin-bottom: 0.5rem;
        }

        .stat-label-landing {
            font-size: 1.1rem;
            opacity: 0.9;
        }

        /* Features Section Mejorado */
        .features-section {
            padding: 6rem 2rem;
            background: #f5f7fa;
        }

        .section-title {
            text-align: center;
            font-size: 2.5rem;
            color: #2d3748;
            margin-bottom: 1rem;
        }

        .section-subtitle {
            text-align: center;
            font-size: 1.2rem;
            color: #718096;
            margin-bottom: 4rem;
        }

        .features-grid {
            max-width: 1200px;
            margin: 0 auto;
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(320px, 1fr));
            gap: 2.5rem;
        }

        .feature-card-landing {
            background: white;
            padding: 2.5rem;
            border-radius: 15px;
            box-shadow: 0 5px 20px rgba(0, 0, 0, 0.08);
            transition: transform 0.3s, box-shadow 0.3s;
            text-align: center;
        }

        .feature-card-landing:hover {
            transform: translateY(-10px);
            box-shadow: 0 15px 40px rgba(102, 126, 234, 0.2);
        }

        .feature-icon-landing {
            width: 80px;
            height: 80px;
            margin: 0 auto 1.5rem;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 2.5rem;
            color: white;
        }

        .feature-card-landing h3 {
            font-size: 1.5rem;
            color: #2d3748;
            margin-bottom: 1rem;
        }

        .feature-card-landing p {
            color: #718096;
            line-height: 1.8;
        }

        /* CTA Section */
        .cta-section {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            padding: 6rem 2rem;
            text-align: center;
            color: white;
        }

        .cta-section h2 {
            font-size: 2.5rem;
            margin-bottom: 1rem;
        }

        .cta-section p {
            font-size: 1.2rem;
            margin-bottom: 2.5rem;
            opacity: 0.95;
        }

        /* Footer Mejorado */
        .footer-landing {
            background: #2d3748;
            color: white;
            padding: 3rem 2rem;
            text-align: center;
        }

        .footer-content {
            max-width: 1200px;
            margin: 0 auto;
        }

        .footer-links {
            display: flex;
            justify-content: center;
            gap: 2rem;
            margin-bottom: 2rem;
            flex-wrap: wrap;
        }

        .footer-links a {
            color: white;
            text-decoration: none;
            opacity: 0.8;
            transition: opacity 0.3s;
        }

        .footer-links a:hover {
            opacity: 1;
        }

        /* Animations */
        @keyframes fadeIn {
            from { opacity: 0; }
            to { opacity: 1; }
        }

        @keyframes fadeInDown {
            from {
                opacity: 0;
                transform: translateY(-30px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(30px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        /* Responsive */
        @media (max-width: 768px) {
            .logo-landing {
                font-size: 2.5rem;
            }

            .tagline {
                font-size: 1.2rem;
            }

            .cta-buttons-landing {
                flex-direction: column;
            }

            .btn-landing {
                width: 100%;
                justify-content: center;
            }

            .section-title {
                font-size: 2rem;
            }

            .stats-container {
                grid-template-columns: 1fr;
                gap: 2rem;
            }

            .features-grid {
                grid-template-columns: 1fr;
            }
        }
    </style>
</head>

<body>
    <!-- Hero Section -->
    <section class="hero-landing">
        <div class="hero-content-landing">
            <h1 class="logo-landing">🛡️ Quibdó Seguro</h1>
            <p class="tagline">
                Únete a la comunidad que está haciendo de Quibdó un lugar más seguro.
                Reporta, informa y gana recompensas por tu participación.
            </p>
            <div class="cta-buttons-landing">
                <a href="{{ url('/login') }}" class="btn-landing btn-primary-landing">
                    <i class="fas fa-sign-in-alt"></i> Iniciar Sesión
                </a>
                <a href="{{ url('/register') }}" class="btn-landing btn-secondary-landing">
                    <i class="fas fa-user-plus"></i> Registrarse Gratis
                </a>
            </div>
        </div>
    </section>

    <!-- Mapa de Incidentes Públicos -->
    <section class="mapa-publico-section">
        <div class="mapa-publico-container">
            <div class="mapa-publico-header">
                <h2><i class="fas fa-map-marked-alt"></i> Incidentes en Tiempo Real</h2>
                <p>Mantente informado sobre la situación de seguridad en Quibdó</p>
            </div>
            <div class="mapa-publico-wrapper">
                <div id="mapaPublico"></div>
            </div>
        </div>
    </section>

    <!-- Últimos Reportes -->
    <section class="reportes-section">
        <div class="reportes-container">
            <h2 class="section-title">Últimos Reportes</h2>
            <p class="section-subtitle">Los incidentes más recientes reportados por la comunidad</p>
            
            <div class="reportes-grid">
                @forelse($ultimosReportes as $reporte)
                    <div class="reporte-card">
                        <div class="reporte-header">
                            <div class="reporte-tipo tipo-{{ $reporte->id_tipo_incidente }}">
                                @switch($reporte->id_tipo_incidente)
                                    @case(1)
                                        <i class="fas fa-user-secret"></i> Robo
                                        @break
                                    @case(2)
                                        <i class="fas fa-car-crash"></i> Accidente
                                        @break
                                    @case(3)
                                        <i class="fas fa-fist-raised"></i> Violencia
                                        @break
                                    @default
                                        <i class="fas fa-exclamation-triangle"></i> Otro
                                @endswitch
                            </div>
                            <div class="reporte-fecha">
                                {{ $reporte->created_at->diffForHumans() }}
                            </div>
                        </div>
                        <div class="reporte-body">
                            <p class="reporte-descripcion">{{ Str::limit($reporte->descripcion, 100) }}</p>
                            <div class="reporte-ubicacion">
                                <i class="fas fa-map-marker-alt"></i>
                                {{ $reporte->direccion_aproximada }}
                            </div>
                        </div>
                        <div class="reporte-footer">
                            <div class="reporte-estado">
                                <i class="fas fa-check-circle"></i> Publicado
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="no-reportes">
                        <i class="fas fa-info-circle"></i>
                        <p>No hay reportes disponibles en este momento</p>
                    </div>
                @endforelse
            </div>
            
            <div class="reportes-cta">
                <a href="{{ url('/register') }}" class="btn-landing btn-primary-landing">
                    <i class="fas fa-plus-circle"></i> Únete y Reporta
                </a>
            </div>
        </div>
    </section>

    <!-- Stats Section -->
    <section class="stats-section">
        <div class="stats-container">
            <div class="stat-item-landing">
                <div class="stat-icon-landing">
                    <i class="fas fa-users"></i>
                </div>
                <span class="stat-number-landing">{{ $estadisticas['usuarios_activos'] }}+</span>
                <span class="stat-label-landing">Usuarios Activos</span>
            </div>
            <div class="stat-item-landing">
                <div class="stat-icon-landing">
                    <i class="fas fa-exclamation-triangle"></i>
                </div>
                <span class="stat-number-landing">{{ $estadisticas['total_reportes'] }}+</span>
                <span class="stat-label-landing">Reportes Realizados</span>
            </div>
            <div class="stat-item-landing">
                <div class="stat-icon-landing">
                    <i class="fas fa-shield-alt"></i>
                </div>
                <span class="stat-number-landing">{{ $estadisticas['reportes_publicados'] }}+</span>
                <span class="stat-label-landing">Reportes Publicados</span>
            </div>
        </div>
    </section>

    <!-- Features Section -->
    <section class="features-section">
        <h2 class="section-title">¿Cómo Funciona?</h2>
        <p class="section-subtitle">Tres simples pasos para hacer tu comunidad más segura</p>
        
        <div class="features-grid">
            <div class="feature-card-landing">
                <div class="feature-icon-landing">
                    <i class="fas fa-map-marked-alt"></i>
                </div>
                <h3>Mapa Interactivo</h3>
                <p>Visualiza incidentes en tiempo real en un mapa interactivo. Conoce las zonas más seguras y las que requieren atención de las autoridades.</p>
            </div>

            <div class="feature-card-landing">
                <div class="feature-icon-landing">
                    <i class="fas fa-mobile-alt"></i>
                </div>
                <h3>Reporta Fácilmente</h3>
                <p>Informa a la comunidad sobre incidentes en tu zona con solo unos clics. Usa tu GPS para ubicación precisa y adjunta evidencia fotográfica.</p>
            </div>

            <div class="feature-card-landing">
                <div class="feature-icon-landing">
                    <i class="fas fa-gift"></i>
                </div>
                <h3>Gana Recompensas</h3>
                <p>Obtén puntos por cada reporte validado y canjéalos por recompensas en comercios aliados. Tu participación tiene valor.</p>
            </div>

            <div class="feature-card-landing">
                <div class="feature-icon-landing">
                    <i class="fas fa-bell"></i>
                </div>
                <h3>Notificaciones</h3>
                <p>Mantente informado con notificaciones en tiempo real sobre el estado de tus reportes y novedades en tu zona.</p>
            </div>

            <div class="feature-card-landing">
                <div class="feature-icon-landing">
                    <i class="fas fa-users-cog"></i>
                </div>
                <h3>Comunidad Activa</h3>
                <p>Forma parte de una comunidad comprometida con la seguridad. Juntos hacemos la diferencia.</p>
            </div>

            <div class="feature-card-landing">
                <div class="feature-icon-landing">
                    <i class="fas fa-chart-line"></i>
                </div>
                <h3>Estadísticas</h3>
                <p>Accede a estadísticas y análisis sobre la seguridad en tu ciudad. Información transparente y actualizada.</p>
            </div>
        </div>
    </section>

    <!-- CTA Section -->
    <section class="cta-section">
        <h2>¿Listo para hacer la diferencia?</h2>
        <p>Únete hoy y sé parte del cambio en tu comunidad</p>
        <div class="cta-buttons-landing">
            <a href="{{ url('/register') }}" class="btn-landing btn-primary-landing">
                <i class="fas fa-rocket"></i> Comenzar Ahora
            </a>
        </div>
    </section>

    <!-- Footer -->
    <footer class="footer-landing">
        <div class="footer-content">
            <div class="footer-links">
                <a href="#"><i class="fas fa-info-circle"></i> Acerca de</a>
                <a href="#"><i class="fas fa-question-circle"></i> Ayuda</a>
                <a href="#"><i class="fas fa-shield-alt"></i> Privacidad</a>
                <a href="#"><i class="fas fa-file-contract"></i> Términos</a>
            </div>
            <p>© {{ date('Y') }} Quibdó Seguro - Todos los derechos reservados.</p>
            <p style="margin-top: 1rem; opacity: 0.8;">
                <i class="fas fa-heart" style="color: #e74c3c;"></i> Hecho con amor para Quibdó
            </p>
        </div>
    </footer>

    <!-- Leaflet JS -->
    <script src="https://unpkg.com/leaflet/dist/leaflet.js"></script>

    <script>
        // Inicializar mapa público
        var mapaPublico = L.map('mapaPublico').setView([5.6947, -76.6611], 13);

        // Cargar mapa base
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            maxZoom: 19,
            attribution: '&copy; OpenStreetMap'
        }).addTo(mapaPublico);

        // Función para obtener color según tipo de incidente
        function getMarkerColor(tipo) {
            switch(parseInt(tipo)) {
                case 1: return '#c53030'; // Robo - Rojo
                case 2: return '#c05621'; // Accidente - Naranja
                case 3: return '#d68910'; // Violencia - Amarillo
                case 4: return '#4a5568'; // Otro - Gris
                default: return '#667eea'; // Default - Azul
            }
        }

        // Función para obtener nombre del tipo
        function getTipoNombre(tipo) {
            switch(parseInt(tipo)) {
                case 1: return 'Robo';
                case 2: return 'Accidente';
                case 3: return 'Violencia';
                case 4: return 'Otro';
                default: return 'Desconocido';
            }
        }

        // Función para crear marcador personalizado
        function createCustomMarker(color) {
            return L.divIcon({
                className: 'custom-marker',
                html: `<div style="
                    width: 16px;
                    height: 16px;
                    border-radius: 50%;
                    background-color: ${color};
                    border: 2px solid white;
                    box-shadow: 0 2px 4px rgba(0,0,0,0.3);
                "></div>`,
                iconSize: [16, 16],
                iconAnchor: [8, 8]
            });
        }

        // Incidentes enviados desde el controlador
        var incidentes = @json($incidentes);

        incidentes.forEach(function(inc) {
            var color = getMarkerColor(inc.id_tipo_incidente);
            var customIcon = createCustomMarker(color);
            
            var marker = L.marker([parseFloat(inc.latitud), parseFloat(inc.longitud)], {
                icon: customIcon
            }).addTo(mapaPublico);
            
            // Formatear fecha
            var fecha = new Date(inc.created_at).toLocaleDateString('es-ES', {
                year: 'numeric',
                month: 'long',
                day: 'numeric'
            });
            
            marker.bindPopup(`
                <div style="min-width: 180px;">
                    <h4 style="margin: 0 0 8px 0; color: ${color};">
                        ${getTipoNombre(inc.id_tipo_incidente)}
                    </h4>
                    <p style="margin: 4px 0; font-size: 13px;"><strong>Ubicación:</strong><br>${inc.direccion_aproximada}</p>
                    <p style="margin: 4px 0; font-size: 12px; color: #666;"><strong>Reportado:</strong> ${fecha}</p>
                </div>
            `);
        });

        // Ajustar vista si hay incidentes
        if (incidentes.length > 0) {
            var group = new L.featureGroup();
            incidentes.forEach(function(inc) {
                var marker = L.marker([parseFloat(inc.latitud), parseFloat(inc.longitud)]);
                group.addLayer(marker);
            });
            mapaPublico.fitBounds(group.getBounds().pad(0.1));
        }
    </script>
</body>
</html>
