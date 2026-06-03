/* index.php */
// Simple animación para los elementos al hacer scroll
        document.addEventListener('DOMContentLoaded', function() {
            const observer = new IntersectionObserver((entries) => {
                entries.forEach(entry => {
                    if (entry.isIntersecting) {
                        entry.target.classList.add('visible');
                    }
                });
            }, { threshold: 0.1 });
            
            document.querySelectorAll('.feature-card').forEach(card => {
                observer.observe(card);
            });
        });

/* mapa.php */
// Esperar a que el DOM esté completamente cargado
document.addEventListener('DOMContentLoaded', function() {
    // Coordenadas del centro de Quibdó [latitud, longitud]
    const center = [5.690, -76.660];
    
    // 1. Inicializar el mapa
    const map = L.map('map').setView(center, 14);
    
    // 2. Añadir capa base de OpenStreetMap
    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '&copy; <a href="https://www.openstreetmap.org/copyright" target="_blank">OpenStreetMap</a> contributors',
        maxZoom: 19
    }).addTo(map);
    
    // 3. Marcador de prueba (verifica que el mapa funciona)
    L.marker(center)
        .addTo(map)
        .bindPopup('<b>Quibdó</b><br>Ubicación central')
        .openPopup();
    
    // 4. Cargar incidentes desde la API
    loadIncidents();
    
    function loadIncidents() {
        fetch('api/incidentes.php')
            .then(response => {
                if (!response.ok) throw new Error('Error en la red');
                return response.json();
            })
            .then(data => {
                // Verificar que tenemos datos
                if (!data || data.length === 0) {
                    console.warn('No se encontraron incidentes');
                    return;
                }
                
                // Procesar cada incidente
                data.forEach(incidente => {
                    // Validar coordenadas
                    if (!incidente.latitud || !incidente.longitud) {
                        console.warn('Incidente sin coordenadas:', incidente.id_incidente);
                        return;
                    }
                    
                    // Crear marcador
                    const marker = L.marker([
                        parseFloat(incidente.latitud),
                        parseFloat(incidente.longitud)
                    ]).addTo(map);
                    
                    // Contenido del popup
                    let popupContent = `
                        <b>${incidente.nombre_tipo || 'Incidente'}</b><br>
                        <small>${incidente.descripcion || 'Sin descripción'}</small><br>
                        <em>Estado: ${incidente.nombre_estado || 'Desconocido'}</em>
                    `;
                    
                    if (incidente.fecha_hora_incidente) {
                        popupContent += `<br><small>Fecha: ${new Date(incidente.fecha_hora_incidente).toLocaleString()}</small>`;
                    }
                    
                    // Añadir popup al marcador
                    marker.bindPopup(popupContent);
                });
            })
            .catch(error => {
                console.error('Error al cargar incidentes:', error);
                // Mostrar error en el mapa
                L.popup()
                    .setLatLng(center)
                    .setContent(`Error: ${error.message}`)
                    .openOn(map);
            });
    }
});

/* perfil.php */
// Cerrar menú desplegable al hacer clic en un enlace
    document.querySelectorAll('.user-dropdown a').forEach(link => {
        link.addEventListener('click', () => {
            document.querySelector('.user-dropdown').style.display = 'none';
        });
    });

/* recompensas.php */
// Cerrar modal al hacer clic fuera
window.onclick = function(event) {
    if (event.target == document.getElementById('modal-agregar')) {
        document.getElementById('modal-agregar').style.display = 'none';
    }
}

/* reportar.php */
// Script para el mapa y geolocalización
document.addEventListener('DOMContentLoaded', function() {
    const map = L.map('map').setView([5.690, -76.660], 13); // Coordenadas aproximadas de Quibdó
    
    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
    }).addTo(map);
    
    let marker;
    map.on('click', function(e) {
        if (marker) map.removeLayer(marker);
        marker = L.marker(e.latlng).addTo(map);
        document.getElementById('latitud').value = e.latlng.lat;
        document.getElementById('longitud').value = e.latlng.lng;
    });
    
    document.getElementById('btn-ubicacion').addEventListener('click', function() {
        if (navigator.geolocation) {
            navigator.geolocation.getCurrentPosition(function(position) {
                const latlng = {
                    lat: position.coords.latitude,
                    lng: position.coords.longitude
                };
                map.setView(latlng, 15);
                if (marker) map.removeLayer(marker);
                marker = L.marker(latlng).addTo(map);
                document.getElementById('latitud').value = latlng.lat;
                document.getElementById('longitud').value = latlng.lng;
            });
        } else {
            alert('Geolocalización no soportada por tu navegador');
        }
    });
});

/* estadisticas.php */


/* estadisticas.php */
document.addEventListener('DOMContentLoaded', function() {
    const ctx = document.getElementById('canjesChart').getContext('2d');
    const chart = new Chart(ctx, {
        type: 'bar',
        data: {
            labels: <?= json_encode($labels) ?>,
            datasets: [{
                label: 'Canjes realizados',
                data: <?= json_encode($data) ?>,
                backgroundColor: '#3182ce',
                borderColor: '#2c5282',
                borderWidth: 1
            }]
        },
        options: {
            responsive: true,
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        stepSize: 1
                    }
                }
            }
        }
    });
});

/* header.php */


