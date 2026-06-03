<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Mapa de Incidentes - Quibdó Seguro</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="{{ asset('assets/css/styles.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/pages.css') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

    <!-- Leaflet CSS -->
    <link rel="stylesheet" href="https://unpkg.com/leaflet/dist/leaflet.css"/>
    <style>
        .mapa-main {
            background: #f5f7fa;
            min-height: 100vh;
        }

        .mapa-container {
            max-width: 1400px;
            margin: 2rem auto 1rem;
            padding: 0 2rem;
            display: grid;
            grid-template-columns: 320px 1fr;
            gap: 2rem;
            position: relative;
            z-index: 3;
        }

        .mapa-sidebar {
            display: flex;
            flex-direction: column;
            gap: 1.5rem;
        }

        .filter-card, .legend-card, .stats-card {
            background: white;
            border-radius: 15px;
            padding: 1.5rem;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
        }

        .filter-card h3, .legend-card h3, .stats-card h3 {
            font-size: 1.1rem;
            color: #2d3748;
            margin-bottom: 1rem;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .filter-card h3 i, .legend-card h3 i, .stats-card h3 i {
            color: #667eea;
        }

        .filter-form {
            display: flex;
            flex-direction: column;
            gap: 1rem;
        }

        .filter-group label {
            display: block;
            margin-bottom: 0.5rem;
            font-weight: 600;
            color: #4a5568;
            font-size: 0.9rem;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .filter-group label i {
            color: #667eea;
        }

        .filter-group select {
            width: 100%;
            padding: 0.75rem;
            border: 2px solid #e2e8f0;
            border-radius: 8px;
            font-size: 0.9rem;
            transition: border-color 0.3s;
        }

        .filter-group select:focus {
            outline: none;
            border-color: #667eea;
        }

        .btn-clear-filters {
            display: block;
            width: 100%;
            padding: 0.75rem;
            background: #f8f9fa;
            color: #e74c3c;
            text-align: center;
            border-radius: 8px;
            text-decoration: none;
            font-weight: 600;
            transition: all 0.3s;
            border: 2px solid #e74c3c;
        }

        .btn-clear-filters:hover {
            background: #e74c3c;
            color: white;
        }

        .legend-items {
            display: flex;
            flex-direction: column;
            gap: 0.75rem;
        }

        .legend-item {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            padding: 0.75rem;
            background: #f8f9fa;
            border-radius: 8px;
            border: 1px solid #e2e8f0;
            transition: all 0.3s;
        }

        .legend-item:hover {
            background: #e2e8f0;
            transform: translateX(3px);
        }

        .legend-marker {
            width: 20px;
            height: 20px;
            border-radius: 50%;
            border: 3px solid white;
            box-shadow: 0 2px 6px rgba(0, 0, 0, 0.3);
            flex-shrink: 0;
        }

        .marker-robo { background: #c53030; }
        .marker-accidente { background: #c05621; }
        .marker-violencia { background: #d68910; }
        .marker-otro { background: #4a5568; }

        .legend-item span:last-child {
            font-weight: 600;
            color: #2d3748;
        }

        /* Estilos para marcadores personalizados */
        .custom-marker {
            background: transparent !important;
            border: none !important;
        }

        /* Mejorar popups */
        .leaflet-popup-content-wrapper {
            border-radius: 10px;
            box-shadow: 0 5px 20px rgba(0, 0, 0, 0.2);
        }

        .leaflet-popup-content {
            margin: 15px;
            line-height: 1.4;
        }

        .stat-item-mapa {
            display: flex;
            align-items: center;
            gap: 1rem;
            padding: 1rem;
            background: #f8f9fa;
            border-radius: 8px;
        }

        .stat-item-mapa i {
            font-size: 2rem;
            color: #667eea;
        }

        .stat-item-mapa .stat-number {
            display: block;
            font-size: 1.5rem;
            font-weight: 700;
            color: #667eea;
        }

        .stat-item-mapa .stat-label {
            display: block;
            font-size: 0.85rem;
            color: #718096;
        }

        .mapa-content {
            background: white;
            border-radius: 15px;
            padding: 1.5rem;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
        }

        .map-wrapper {
            border-radius: 10px;
            overflow: hidden;
        }

        #map {
            height: 600px;
            width: 100%;
            border-radius: 10px;
        }

        @media (max-width: 1024px) {
            .mapa-container {
                grid-template-columns: 1fr;
            }
            
            .mapa-sidebar {
                order: 2;
            }
            
            .mapa-content {
                order: 1;
            }
        }

        @media (max-width: 768px) {
            #map {
                height: 400px;
            }
        }
    </style>
</head>
<body>
@include('components.header')

<main class="mapa-main">
    <!-- Hero Section -->
    <div class="page-hero">
        <h1><i class="fas fa-map-marked-alt"></i> Mapa de Incidentes</h1>
        <p>Visualiza en tiempo real los incidentes reportados en tu comunidad</p>
    </div>

    <div class="mapa-container">
        <div class="mapa-sidebar">
            <!-- Filtros -->
            <div class="filter-card">
                <h3><i class="fas fa-filter"></i> Filtros</h3>
                <form method="GET" action="{{ url('/mapa') }}" class="filter-form">
                    <div class="filter-group">
                        <label><i class="fas fa-exclamation-triangle"></i> Tipo de Incidente</label>
                        <select name="tipo" onchange="this.form.submit()">
                            <option value="">Todos los tipos</option>
                            <option value="1" {{ request('tipo') == '1' ? 'selected' : '' }}>🔴 Robo</option>
                            <option value="2" {{ request('tipo') == '2' ? 'selected' : '' }}>🟠 Accidente</option>
                            <option value="3" {{ request('tipo') == '3' ? 'selected' : '' }}>🟡 Violencia</option>
                            <option value="4" {{ request('tipo') == '4' ? 'selected' : '' }}>⚪ Otro</option>
                        </select>
                    </div>



                    <div class="filter-group">
                        <label><i class="fas fa-calendar"></i> Periodo</label>
                        <select name="periodo" onchange="this.form.submit()">
                            <option value="">Todo el tiempo</option>
                            <option value="24h" {{ request('periodo') == '24h' ? 'selected' : '' }}>Últimas 24 horas</option>
                            <option value="7d" {{ request('periodo') == '7d' ? 'selected' : '' }}>Última semana</option>
                            <option value="30d" {{ request('periodo') == '30d' ? 'selected' : '' }}>Último mes</option>
                        </select>
                    </div>

                    @if(request()->hasAny(['tipo', 'periodo']))
                        <a href="{{ url('/mapa') }}" class="btn-clear-filters">
                            <i class="fas fa-times-circle"></i> Limpiar Filtros
                        </a>
                    @endif
                </form>
            </div>

            <!-- Leyenda -->
            <div class="legend-card">
                <h3><i class="fas fa-info-circle"></i> Leyenda</h3>
                <div class="legend-items">
                    <div class="legend-item">
                        <span class="legend-marker marker-robo"></span>
                        <span>Robo</span>
                    </div>
                    <div class="legend-item">
                        <span class="legend-marker marker-accidente"></span>
                        <span>Accidente</span>
                    </div>
                    <div class="legend-item">
                        <span class="legend-marker marker-violencia"></span>
                        <span>Violencia</span>
                    </div>
                    <div class="legend-item">
                        <span class="legend-marker marker-otro"></span>
                        <span>Otro</span>
                    </div>
                </div>
            </div>

            <!-- Estadísticas -->
            <div class="stats-card">
                <h3><i class="fas fa-chart-bar"></i> Estadísticas</h3>
                <div class="stat-item-mapa">
                    <i class="fas fa-map-pin"></i>
                    <div>
                        <span class="stat-number">{{ count($incidentes) }}</span>
                        <span class="stat-label">Incidentes Mostrados</span>
                    </div>
                </div>
            </div>
        </div>

        <div class="mapa-content">
            <div class="map-wrapper">
                <div id="map"></div>
            </div>
        </div>
    </div>
</main>

<footer style="margin-top: 1rem; padding: 1rem 0;">
    <p>© {{ date('Y') }} Quibdó Seguro</p>
</footer>

<!-- Leaflet JS -->
<script src="https://unpkg.com/leaflet/dist/leaflet.js"></script>

<script>
    // Inicializar mapa
    var map = L.map('map').setView([5.6947, -76.6611], 13); // Coordenadas de Quibdó aprox

    // Cargar mapa base
    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        maxZoom: 19,
        attribution: '&copy; OpenStreetMap'
    }).addTo(map);

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

    // Función para obtener nombre del estado
    function getEstadoNombre(estado) {
        switch(parseInt(estado)) {
            case 1: return 'Pendiente';
            case 2: return 'Validado';
            case 3: return 'Rechazado';
            default: return 'Desconocido';
        }
    }

    // Función para crear marcador personalizado
    function createCustomMarker(color) {
        return L.divIcon({
            className: 'custom-marker',
            html: `<div style="
                width: 20px;
                height: 20px;
                border-radius: 50%;
                background-color: ${color};
                border: 3px solid white;
                box-shadow: 0 2px 6px rgba(0,0,0,0.3);
            "></div>`,
            iconSize: [20, 20],
            iconAnchor: [10, 10]
        });
    }

    // Reportes enviados desde el controlador
    var incidentes = @json($incidentes);

    incidentes.forEach(function(inc) {
        var color = getMarkerColor(inc.id_tipo_incidente);
        var customIcon = createCustomMarker(color);
        
        var marker = L.marker([parseFloat(inc.latitud), parseFloat(inc.longitud)], {
            icon: customIcon
        }).addTo(map);
        
        // Formatear fecha
        var fecha = new Date(inc.created_at).toLocaleDateString('es-ES', {
            year: 'numeric',
            month: 'long',
            day: 'numeric',
            hour: '2-digit',
            minute: '2-digit'
        });
        
        marker.bindPopup(`
            <div style="min-width: 200px;">
                <h4 style="margin: 0 0 10px 0; color: ${color};">
                    ${getTipoNombre(inc.id_tipo_incidente)}
                </h4>
                <p style="margin: 5px 0;"><strong>Descripción:</strong><br>${inc.descripcion}</p>
                <p style="margin: 5px 0;"><strong>Dirección:</strong><br>${inc.direccion_aproximada}</p>
                <p style="margin: 5px 0;"><strong>Estado:</strong> 
                    <span style="padding: 2px 8px; border-radius: 12px; font-size: 12px; background: ${inc.id_estado == 2 ? '#d4edda' : inc.id_estado == 3 ? '#f8d7da' : '#fff3cd'}; color: ${inc.id_estado == 2 ? '#155724' : inc.id_estado == 3 ? '#721c24' : '#856404'};">
                        ${getEstadoNombre(inc.id_estado)}
                    </span>
                </p>
                <p style="margin: 5px 0; font-size: 12px; color: #666;"><strong>Reportado:</strong> ${fecha}</p>
                ${inc.evidencia_foto_url ? `<img src="/storage/${inc.evidencia_foto_url}" style="width: 100%; max-width: 200px; border-radius: 5px; margin-top: 10px;">` : ''}
            </div>
        `);
    });

    // Ajustar vista si hay incidentes
    if (incidentes.length > 0) {
        var group = new L.featureGroup(map._layers);
        if (Object.keys(group._layers).length > 0) {
            map.fitBounds(group.getBounds().pad(0.1));
        }
    }
</script>
</body>
</html>
