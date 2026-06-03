<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Reportar Incidente - Quibdó Seguro</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="{{ asset('assets/css/styles.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/pages.css') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body>
@include('components.header')

<main class="reportar-main">
    <!-- Hero Section -->
    <div class="page-hero">
        <h1><i class="fas fa-exclamation-triangle"></i> Reportar Incidente</h1>
        <p>Ayuda a tu comunidad reportando incidentes de seguridad</p>
    </div>

    <div class="modern-container">
        <div class="modern-card">
            <div class="modern-card-header">
                <h2><i class="fas fa-file-alt"></i> Formulario de Reporte</h2>
            </div>

            <!-- Mensajes de éxito o error -->
            @if(session('success'))
                <div class="alert-modern alert-modern-success">
                    <i class="fas fa-check-circle"></i>
                    <span>{{ session('success') }}</span>
                </div>
            @endif

            @if(session('warning'))
                <div class="alert-modern alert-modern-warning">
                    <i class="fas fa-exclamation-triangle"></i>
                    <span>{{ session('warning') }}</span>
                </div>
            @endif

            @if ($errors->any())
                <div class="alert-modern alert-modern-error">
                    <i class="fas fa-times-circle"></i>
                    <div>
                        @foreach ($errors->all() as $error)
                            <div>{{ $error }}</div>
                        @endforeach
                    </div>
                </div>
            @endif

        <!-- Formulario para crear incidente -->
        <form action="{{ url('/reportar') }}" method="POST" enctype="multipart/form-data">
            @csrf

            <div class="modern-form-group">
                <label for="tipo">
                    <i class="fas fa-list"></i> Tipo de Incidente
                </label>
                <select name="id_tipo_incidente" id="tipo" required>
                    <option value="">Seleccione el tipo...</option>
                    <option value="1">🔴 Robo</option>
                    <option value="2">🟠 Accidente</option>
                    <option value="3">🟡 Violencia</option>
                    <option value="4">⚪ Otro</option>
                </select>
            </div>

            <div class="modern-form-group">
                <label for="descripcion">
                    <i class="fas fa-align-left"></i> Descripción del Incidente
                </label>
                <textarea name="descripcion" id="descripcion" rows="4" required placeholder="Describe lo que sucedió..."></textarea>
            </div>

            <div class="form-group">
                <label for="ubicacion">📍 Ubicación del Incidente</label>
                <div style="background: #f8f9fa; padding: 15px; border-radius: 8px; margin-bottom: 10px;">
                    <p style="color: #666; font-size: 14px; margin: 0 0 10px 0;">
                        <strong>Instrucciones:</strong><br>
                        • Haz clic en el mapa para seleccionar la ubicación exacta<br>
                        • Arrastra el marcador para ajustar la posición<br>
                        • O usa el botón para obtener tu ubicación actual
                    </p>
                    <button type="button" class="btn btn-acento" onclick="obtenerUbicacion()" style="width: 100%;">
                        📍 Usar mi ubicación actual (GPS)
                    </button>
                </div>
                
                <!-- Mapa interactivo -->
                <div id="mapa-selector" style="height: 450px; width: 100%; border-radius: 8px; border: 2px solid #ddd; box-shadow: 0 2px 8px rgba(0,0,0,0.1);"></div>
                
                <div id="ubicacion-seleccionada" style="margin-top: 15px; padding: 12px; background: #d4edda; border-left: 4px solid #28a745; border-radius: 5px; display: none;">
                    <strong style="color: #155724;">✓ Ubicación seleccionada</strong><br>
                    <span id="coords-display" style="color: #155724; font-size: 13px;"></span>
                </div>
            </div>

            <div class="modern-form-group">
                <label for="direccion">
                    <i class="fas fa-map-marker-alt"></i> Dirección Aproximada
                </label>
                <input type="text" name="direccion_aproximada" id="direccion" required placeholder="Se completará automáticamente al seleccionar en el mapa">
            </div>

            <!-- Campos ocultos para latitud y longitud -->
            <input type="hidden" name="latitud" id="latitud" required>
            <input type="hidden" name="longitud" id="longitud" required>

            <div class="modern-form-group">
                <label for="evidencia">
                    <i class="fas fa-camera"></i> Evidencia Fotográfica (Opcional)
                </label>
                <input type="file" name="evidencia_foto" id="evidencia" accept="image/*">
                <small style="color: #718096; font-size: 0.85rem;">Formatos aceptados: JPG, PNG. Máximo 2MB</small>
            </div>

            <button type="submit" class="btn-modern" style="width: 100%; justify-content: center; font-size: 1.1rem; padding: 1rem;">
                <i class="fas fa-paper-plane"></i> Enviar Reporte
            </button>
        </form>
        </div>
    </div>
</main>

<style>
.reportar-main {
    background: #f5f7fa;
    min-height: 100vh;
}
</style>

<footer>
    <p>© {{ date('Y') }} Quibdó Seguro - Todos los derechos reservados.</p>
</footer>

<!-- Leaflet para mapa interactivo -->
<link rel="stylesheet" href="https://unpkg.com/leaflet/dist/leaflet.css"/>
<script src="https://unpkg.com/leaflet/dist/leaflet.js"></script>

<script>
let map = null;
let marker = null;

// Inicializar mapa al cargar la página
document.addEventListener('DOMContentLoaded', function() {
    inicializarMapa();
});

function inicializarMapa() {
    // Coordenadas de Quibdó por defecto
    const quibdoLat = 5.6947;
    const quibdoLng = -76.6611;
    
    map = L.map('mapa-selector').setView([quibdoLat, quibdoLng], 13);
    
    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        maxZoom: 19,
        attribution: '© OpenStreetMap'
    }).addTo(map);
    
    // Agregar instrucciones en el mapa
    const info = L.control({position: 'topright'});
    info.onAdd = function() {
        const div = L.DomUtil.create('div', 'info-box');
        div.innerHTML = '<strong>👆 Haz clic aquí</strong><br><small>para marcar la ubicación</small>';
        div.style.background = 'rgba(255, 255, 255, 0.95)';
        div.style.padding = '12px 15px';
        div.style.borderRadius = '8px';
        div.style.boxShadow = '0 2px 8px rgba(0,0,0,0.2)';
        div.style.border = '2px solid #4CAF50';
        div.id = 'info-instruccion';
        return div;
    };
    info.addTo(map);
    
    // Evento de clic en el mapa
    map.on('click', function(e) {
        seleccionarUbicacion(e.latlng.lat, e.latlng.lng);
    });
}

function obtenerUbicacion() {
    if (navigator.geolocation) {
        navigator.geolocation.getCurrentPosition(function(position) {
            const lat = position.coords.latitude;
            const lng = position.coords.longitude;
            
            seleccionarUbicacion(lat, lng);
            map.setView([lat, lng], 16);
        }, function(error) {
            alert('No se pudo obtener tu ubicación. Por favor, haz clic en el mapa para seleccionar manualmente.');
        });
    } else {
        alert('Tu navegador no soporta geolocalización. Por favor, haz clic en el mapa para seleccionar manualmente.');
    }
}

function seleccionarUbicacion(lat, lng) {
    // Guardar coordenadas en campos ocultos
    document.getElementById('latitud').value = lat;
    document.getElementById('longitud').value = lng;
    
    // Mostrar coordenadas
    document.getElementById('coords-display').textContent = `Coordenadas: ${lat.toFixed(6)}, ${lng.toFixed(6)}`;
    document.getElementById('ubicacion-seleccionada').style.display = 'block';
    
    // Cambiar cursor del mapa
    document.getElementById('mapa-selector').classList.add('ubicacion-seleccionada');
    
    // Ocultar instrucciones
    const infoBox = document.getElementById('info-instruccion');
    if (infoBox) {
        infoBox.style.display = 'none';
    }
    
    // Obtener dirección aproximada usando reverse geocoding
    document.getElementById('direccion').value = 'Obteniendo dirección...';
    fetch(`https://nominatim.openstreetmap.org/reverse?format=json&lat=${lat}&lon=${lng}`)
        .then(response => response.json())
        .then(data => {
            if (data.display_name) {
                document.getElementById('direccion').value = data.display_name;
            } else {
                document.getElementById('direccion').value = `Ubicación: ${lat.toFixed(4)}, ${lng.toFixed(4)}`;
            }
        })
        .catch(error => {
            document.getElementById('direccion').value = `Ubicación: ${lat.toFixed(4)}, ${lng.toFixed(4)}`;
        });
    
    // Actualizar o crear marcador
    if (marker !== null) {
        map.removeLayer(marker);
    }
    
    // Crear icono personalizado
    const customIcon = L.divIcon({
        className: 'custom-marker',
        html: '<div style="background: #ff4444; width: 30px; height: 30px; border-radius: 50% 50% 50% 0; transform: rotate(-45deg); border: 3px solid white; box-shadow: 0 2px 8px rgba(0,0,0,0.3);"><div style="width: 10px; height: 10px; background: white; border-radius: 50%; position: absolute; top: 7px; left: 7px;"></div></div>',
        iconSize: [30, 30],
        iconAnchor: [15, 30]
    });
    
    marker = L.marker([lat, lng], {
        draggable: true,
        icon: customIcon
    }).addTo(map);
    
    marker.bindPopup('<strong>📍 Ubicación del incidente</strong><br><small>Arrastra para ajustar la posición</small>').openPopup();
    
    // Permitir arrastrar el marcador
    marker.on('dragend', function(e) {
        const newPos = e.target.getLatLng();
        seleccionarUbicacion(newPos.lat, newPos.lng);
    });
}

// Validación antes de enviar
document.querySelector('form').addEventListener('submit', function(e) {
    const lat = document.getElementById('latitud').value;
    const lng = document.getElementById('longitud').value;
    
    if (!lat || !lng) {
        e.preventDefault();
        alert('Por favor, selecciona una ubicación en el mapa o usa tu GPS.');
        return false;
    }
});
</script>

<style>
.info-box {
    font-size: 14px;
    text-align: center;
}

/* Animación para el marcador */
@keyframes bounce {
    0%, 100% { transform: translateY(0); }
    50% { transform: translateY(-10px); }
}

.leaflet-marker-icon {
    animation: bounce 0.5s ease-in-out;
}

/* Mejorar el popup */
.leaflet-popup-content-wrapper {
    border-radius: 8px;
    box-shadow: 0 3px 14px rgba(0,0,0,0.4);
}

.leaflet-popup-content {
    margin: 13px 19px;
    line-height: 1.4;
    font-size: 13px;
}

/* Cursor del mapa */
#mapa-selector {
    cursor: crosshair;
}

#mapa-selector.ubicacion-seleccionada {
    cursor: default;
}
</style>
</body>
</html>
