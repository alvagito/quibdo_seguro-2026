# 📚 Documentación Técnica Completa - Quibdó Seguro

## 🏗️ Arquitectura del Sistema

### **Patrón MVC (Modelo-Vista-Controlador)**
Quibdó Seguro está construido siguiendo el patrón arquitectónico MVC de Laravel:

```
┌─────────────────┐    ┌─────────────────┐    ┌─────────────────┐
│     MODELO      │    │   CONTROLADOR   │    │      VISTA      │
│                 │    │                 │    │                 │
│ - User.php      │◄──►│ AuthController  │◄──►│ login.blade.php │
│ - Incidente.php │    │ AdminController │    │ dashboard.blade │
│ - Oferta.php    │    │ ComercioController│   │ mapa.blade.php  │
│ - Canje.php     │    │ UsuarioController│    │ etc...          │
└─────────────────┘    └─────────────────┘    └─────────────────┘
```

## 📁 Estructura de Directorios Explicada

### **🔧 `/app` - Lógica de la Aplicación**

#### **`/app/Http/Controllers` - Controladores**
Los controladores manejan la lógica de negocio y coordinan entre modelos y vistas.

**📄 AuthController.php**
```php
<?php
namespace App\Http\Controllers;

class AuthController extends Controller
{
    // Maneja autenticación de usuarios
    public function login()      // Muestra formulario de login
    public function authenticate() // Procesa login
    public function register()   // Muestra formulario de registro
    public function store()      // Procesa registro
    public function logout()     // Cierra sesión
}
```
**¿Por qué existe?** Centraliza toda la lógica de autenticación (login, registro, logout).

**📄 UsuarioController.php**
```php
<?php
class UsuarioController extends Controller
{
    // Funciones principales del usuario
    public function dashboard()        // Panel principal del usuario
    public function reportarIncidente() // Formulario para reportar
    public function storeIncidente()   // Guarda el reporte
    public function mapa()            // Muestra mapa interactivo
    public function recompensas()     // Lista recompensas disponibles
    public function canjearRecompensa() // Procesa canje
    public function misCanjes()       // Historial de canjes
    public function perfil()          // Perfil del usuario
}
```
**¿Por qué existe?** Maneja todas las funciones que puede realizar un usuario normal.

**📄 AdminController.php**
```php
<?php
class AdminController extends Controller
{
    // Gestión administrativa
    public function index()           // Dashboard admin
    public function usuarios()        // Lista usuarios
    public function editUsuario()     // Editar usuario
    public function reportes()        // Gestionar reportes
    public function comercios()       // Lista comercios
    public function ofertas()         // Gestionar ofertas
    public function recompensas()     // Gestionar recompensas del sistema
}
```
**¿Por qué existe?** Centraliza todas las funciones administrativas del sistema.

**📄 ComercioController.php**
```php
<?php
class ComercioController extends Controller
{
    // Panel de comercios
    public function index()           // Dashboard comercio
    public function ofertas()         // Gestionar ofertas
    public function storeOferta()     // Crear oferta
    public function estadisticas()    // Ver métricas
    public function validarCanje()    // Validar códigos QR
}
```
**¿Por qué existe?** Maneja las funciones específicas de los comercios aliados.

**📄 DashboardController.php**
```php
<?php
class DashboardController extends Controller
{
    // Dashboard principal
    public function index()
    {
        // Calcula estadísticas en tiempo real
        $totalReportes = Incidente::count();
        $puntosUsuario = Auth::user()->puntos;
        $reportesUsuario = Incidente::where('id_usuario', Auth::id())->count();
        
        return view('dashboard', compact('totalReportes', 'puntosUsuario', 'reportesUsuario'));
    }
}
```
**¿Por qué existe?** Centraliza la lógica del dashboard principal con métricas.

#### **`/app/Models` - Modelos de Datos**

**📄 User.php**
```php
<?php
namespace App\Models;
use MongoDB\Laravel\Eloquent\Model;

class User extends Model
{
    protected $connection = 'mongodb';  // Usa MongoDB
    protected $collection = 'usuarios'; // Nombre de la colección
    
    protected $fillable = [
        'nombre', 'email', 'password', 'rol', 'puntos'
    ];
    
    // Relaciones
    public function incidentes()
    {
        return $this->hasMany(Incidente::class, 'id_usuario');
    }
    
    public function ofertas()
    {
        return $this->hasMany(Oferta::class, 'id_comercio');
    }
}
```
**¿Por qué existe?** Representa a los usuarios del sistema y define sus relaciones.

**📄 Incidente.php**
```php
<?php
class Incidente extends Model
{
    protected $connection = 'mongodb';
    protected $collection = 'incidentes';
    
    protected $fillable = [
        'id_usuario', 'descripcion', 'latitud', 'longitud', 
        'direccion_aproximada', 'id_tipo_incidente'
    ];
    
    // Relación con usuario
    public function usuario()
    {
        return $this->belongsTo(User::class, 'id_usuario');
    }
}
```
**¿Por qué existe?** Representa los reportes de incidentes con su geolocalización.

**📄 Oferta.php**
```php
<?php
class Oferta extends Model
{
    protected $fillable = [
        'id_comercio', 'titulo', 'descripcion', 'puntos'
    ];
    
    // Relación con comercio
    public function comercio()
    {
        return $this->belongsTo(User::class, 'id_comercio');
    }
    
    // Relación con canjes
    public function canjes()
    {
        return $this->hasMany(Canje::class, 'id_oferta');
    }
}
```
**¿Por qué existe?** Representa las ofertas que publican los comercios.

**📄 Canje.php**
```php
<?php
class Canje extends Model
{
    protected $fillable = [
        'id_usuario', 'id_oferta', 'id_comercio', 
        'puntos_canjeados', 'codigo_qr', 'estado'
    ];
    
    protected $casts = [
        'fecha_canje' => 'datetime',
        'fecha_validacion' => 'datetime'
    ];
    
    // Relaciones
    public function usuario() { return $this->belongsTo(User::class, 'id_usuario'); }
    public function oferta() { return $this->belongsTo(Oferta::class, 'id_oferta'); }
    public function comercio() { return $this->belongsTo(User::class, 'id_comercio'); }
}
```
**¿Por qué existe?** Representa los canjes de recompensas con códigos QR.

#### **`/app/Services` - Servicios**

**📄 NotificacionService.php**
```php
<?php
namespace App\Services;

class NotificacionService
{
    public static function crear($idUsuario, $mensaje, $tipo = 'info')
    {
        return Notificacion::create([
            'id_usuario' => $idUsuario,
            'mensaje' => $mensaje,
            'tipo' => $tipo,
            'leida' => false
        ]);
    }
    
    public static function marcarLeida($id)
    {
        $notificacion = Notificacion::findOrFail($id);
        $notificacion->leida = true;
        $notificacion->save();
        return $notificacion;
    }
}
```
**¿Por qué existe?** Centraliza la lógica de notificaciones para reutilizar código.

### **🎨 `/resources/views` - Vistas (Frontend)**

#### **Estructura de Vistas**
```
resources/views/
├── 📁 admin/           # Vistas del panel administrativo
├── 📁 comercio/        # Vistas del panel de comercios
├── 📁 components/      # Componentes reutilizables
├── 📁 layouts/         # Plantillas base
├── 📄 login.blade.php  # Página de login
├── 📄 dashboard.blade.php # Dashboard principal
├── 📄 mapa.blade.php   # Mapa interactivo
└── 📄 etc...
```

#### **📄 components/header.blade.php**
```php
<header class="modern-header">
    <div class="header-container">
        <div class="logo">
            <h1><a href="{{ url('/') }}">Quibdó Seguro</a></h1>
        </div>
        <nav class="main-nav">
            <ul>
                @auth
                    @if(Auth::user()->rol == 'admin')
                        <li><a href="{{ url('/admin') }}">Dashboard</a></li>
                        <li><a href="{{ url('/admin/usuarios') }}">Usuarios</a></li>
                        <!-- Más enlaces de admin -->
                    @elseif(Auth::user()->rol == 'comercio')
                        <li><a href="{{ url('/comercio') }}">Panel</a></li>
                        <!-- Enlaces de comercio -->
                    @else
                        <li><a href="{{ url('/dashboard') }}">Dashboard</a></li>
                        <!-- Enlaces de usuario normal -->
                    @endif
                @endauth
            </ul>
        </nav>
    </div>
</header>
```
**¿Por qué existe?** Proporciona navegación consistente y menús dinámicos según el rol del usuario.

#### **📄 dashboard.blade.php**
```php
@extends('layouts.app')

@section('content')
<main class="dashboard-main">
    <div class="page-hero">
        <h1>Dashboard</h1>
        <p>Bienvenido {{ Auth::user()->nombre }}</p>
    </div>
    
    <div class="stats-grid">
        <div class="stat-card">
            <div class="stat-number">{{ $totalReportes }}</div>
            <div class="stat-label">Reportes Totales</div>
        </div>
        <!-- Más estadísticas -->
    </div>
    
    <div class="recent-reports">
        @foreach($reportesRecientes as $reporte)
            <div class="report-card">
                <h3>{{ $reporte->descripcion }}</h3>
                <p>{{ $reporte->created_at->format('d/m/Y') }}</p>
            </div>
        @endforeach
    </div>
</main>
@endsection
```
**¿Por qué existe?** Muestra el panel principal con estadísticas y reportes recientes.

#### **📄 mapa.blade.php**
```php
<!DOCTYPE html>
<html>
<head>
    <link rel="stylesheet" href="https://unpkg.com/leaflet/dist/leaflet.css" />
</head>
<body>
    @include('components.header')
    
    <main>
        <div id="mapa" style="height: 600px;"></div>
    </main>
    
    <script src="https://unpkg.com/leaflet/dist/leaflet.js"></script>
    <script>
        // Inicializar mapa
        var mapa = L.map('mapa').setView([5.6945, -76.6581], 13);
        
        // Agregar capa de mapa
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png').addTo(mapa);
        
        // Cargar incidentes desde el servidor
        @foreach($incidentes as $incidente)
            L.marker([{{ $incidente->latitud }}, {{ $incidente->longitud }}])
             .addTo(mapa)
             .bindPopup('{{ $incidente->descripcion }}');
        @endforeach
    </script>
</body>
</html>
```
**¿Por qué existe?** Muestra un mapa interactivo con los incidentes reportados.

### **🛣️ `/routes/web.php` - Definición de Rutas**

```php
<?php
use Illuminate\Support\Facades\Route;

// Rutas públicas
Route::get('/', [InicioController::class, 'index']);
Route::get('/login', [AuthController::class, 'login']);
Route::post('/login', [AuthController::class, 'authenticate']);

// Rutas protegidas (requieren autenticación)
Route::middleware(['auth'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index']);
    Route::get('/mapa', [UsuarioController::class, 'mapa']);
    Route::post('/reportar', [UsuarioController::class, 'storeIncidente']);
});

// Rutas de administrador
Route::middleware(['auth', 'role:admin'])->group(function () {
    Route::get('/admin', [AdminController::class, 'index']);
    Route::get('/admin/usuarios', [AdminController::class, 'usuarios']);
});

// Rutas de comercio
Route::middleware(['auth', 'role:comercio'])->group(function () {
    Route::get('/comercio', [ComercioController::class, 'index']);
    Route::post('/comercio/ofertas', [ComercioController::class, 'storeOferta']);
});
```
**¿Por qué existe?** Define qué URL lleva a qué controlador y método, con protección por roles.

### **🎨 `/public/assets` - Recursos Estáticos**

#### **📄 `/public/assets/css/styles.css`**
```css
/* Estilos globales */
:root {
    --primary-color: #667eea;
    --secondary-color: #764ba2;
    --success-color: #4facfe;
    --warning-color: #f093fb;
}

.modern-header {
    background: linear-gradient(135deg, var(--primary-color) 0%, var(--secondary-color) 100%);
    padding: 1rem 0;
    box-shadow: 0 2px 10px rgba(0,0,0,0.1);
}

.btn-modern {
    background: linear-gradient(135deg, var(--primary-color) 0%, var(--secondary-color) 100%);
    color: white;
    padding: 0.75rem 1.5rem;
    border: none;
    border-radius: 8px;
    transition: transform 0.2s;
}

.btn-modern:hover {
    transform: translateY(-2px);
}
```
**¿Por qué existe?** Define el diseño visual moderno con gradientes y animaciones.

#### **📄 `/public/assets/js/dashboard.js`**
```javascript
// Funcionalidad del dashboard
document.addEventListener('DOMContentLoaded', function() {
    // Inicializar gráficos
    initCharts();
    
    // Actualizar estadísticas cada 30 segundos
    setInterval(updateStats, 30000);
    
    // Manejar filtros
    setupFilters();
});

function initCharts() {
    // Configurar gráficos de estadísticas
    const ctx = document.getElementById('reportesChart').getContext('2d');
    new Chart(ctx, {
        type: 'line',
        data: {
            labels: ['Ene', 'Feb', 'Mar', 'Abr', 'May', 'Jun'],
            datasets: [{
                label: 'Reportes por Mes',
                data: reportesPorMes,
                borderColor: '#667eea',
                tension: 0.4
            }]
        }
    });
}
```
**¿Por qué existe?** Añade interactividad al dashboard con gráficos y actualizaciones en tiempo real.

### **🗄️ `/database` - Base de Datos**

#### **📄 `/database/seeders/MongoDBSeeder.php`**
```php
<?php
namespace Database\Seeders;

class MongoDBSeeder extends Seeder
{
    public function run()
    {
        // Crear usuario administrador
        User::create([
            'nombre' => 'Administrador',
            'email' => 'admin@quibdoseguro.com',
            'password' => Hash::make('admin123'),
            'rol' => 'admin',
            'puntos' => 0
        ]);
        
        // Crear comercio de ejemplo
        User::create([
            'nombre' => 'Comercio Ejemplo',
            'email' => 'comercio@ejemplo.com',
            'password' => Hash::make('comercio123'),
            'rol' => 'comercio',
            'puntos' => 0
        ]);
        
        // Crear recompensas del sistema
        Recompensa::create([
            'nombre' => 'Certificado de Participación',
            'puntos' => 100
        ]);
    }
}
```
**¿Por qué existe?** Crea datos iniciales para probar el sistema.

## 🔄 Flujo de Funcionamiento

### **1. Flujo de Autenticación**
```
Usuario → /login → AuthController@login → login.blade.php
       ↓
Envía formulario → AuthController@authenticate → Valida credenciales
       ↓
Si es válido → Redirect /dashboard → DashboardController@index
```

### **2. Flujo de Reporte de Incidente**
```
Usuario → /reportar → UsuarioController@reportarIncidente → reportar.blade.php
       ↓
Completa formulario → UsuarioController@storeIncidente → Valida datos
       ↓
Guarda en BD → Suma puntos → Redirect /dashboard con mensaje
```

### **3. Flujo de Canje de Recompensa**
```
Usuario → /recompensas → UsuarioController@recompensas → Lista ofertas
       ↓
Selecciona oferta → UsuarioController@canjearRecompensa → Valida puntos
       ↓
Crea Canje → Genera código QR → Resta puntos → Redirect con código
```

### **4. Flujo de Validación de Comercio**
```
Comercio → /comercio/estadisticas → ComercioController@estadisticas
       ↓
Ingresa código QR → ComercioController@validarCanje → Busca canje
       ↓
Si existe → Marca como validado → Redirect con confirmación
```

## 🔐 Sistema de Seguridad

### **Middleware de Autenticación**
```php
// app/Http/Kernel.php
protected $routeMiddleware = [
    'auth' => \App\Http\Middleware\Authenticate::class,
    'role' => \App\Http\Middleware\CheckRole::class,
];
```

### **Middleware de Roles**
```php
// app/Http/Middleware/CheckRole.php
public function handle($request, Closure $next, $role)
{
    if (!Auth::check() || Auth::user()->rol !== $role) {
        abort(403, 'Acceso denegado');
    }
    return $next($request);
}
```

### **Validación de Datos**
```php
// En controladores
$request->validate([
    'descripcion' => 'required|string|max:500',
    'latitud' => 'required|numeric|between:-90,90',
    'longitud' => 'required|numeric|between:-180,180',
]);
```

## 📊 Base de Datos MongoDB

### **Colecciones Principales**
```javascript
// usuarios
{
  "_id": ObjectId,
  "nombre": "Juan Pérez",
  "email": "juan@ejemplo.com",
  "password": "hash_encriptado",
  "rol": "normal", // normal, admin, comercio
  "puntos": 150,
  "created_at": ISODate,
  "updated_at": ISODate
}

// incidentes
{
  "_id": ObjectId,
  "id_usuario": ObjectId,
  "descripcion": "Hueco en la vía",
  "latitud": 5.6945,
  "longitud": -76.6581,
  "direccion_aproximada": "Calle 25 con Carrera 3",
  "id_tipo_incidente": 1,
  "created_at": ISODate
}

// ofertas
{
  "_id": ObjectId,
  "id_comercio": ObjectId,
  "titulo": "20% de descuento",
  "descripcion": "En todos los productos",
  "puntos": 100,
  "created_at": ISODate
}

// canjes
{
  "_id": ObjectId,
  "id_usuario": ObjectId,
  "id_oferta": ObjectId,
  "id_comercio": ObjectId,
  "puntos_canjeados": 100,
  "codigo_qr": "QR123456",
  "estado": "pendiente", // pendiente, validado
  "fecha_canje": ISODate,
  "fecha_validacion": ISODate
}
```

## 🎨 Sistema de Diseño

### **Paleta de Colores**
```css
:root {
    /* Colores principales */
    --primary-gradient: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    --success-gradient: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);
    --warning-gradient: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
    
    /* Colores de texto */
    --text-primary: #2d3748;
    --text-secondary: #718096;
    --text-muted: #a0aec0;
    
    /* Colores de fondo */
    --bg-primary: #ffffff;
    --bg-secondary: #f7fafc;
    --bg-muted: #edf2f7;
}
```

### **Componentes Reutilizables**
```css
/* Tarjetas modernas */
.modern-card {
    background: var(--bg-primary);
    border-radius: 12px;
    box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
    transition: transform 0.3s, box-shadow 0.3s;
}

.modern-card:hover {
    transform: translateY(-2px);
    box-shadow: 0 5px 20px rgba(0, 0, 0, 0.1);
}

/* Botones con gradientes */
.btn-modern {
    background: var(--primary-gradient);
    color: white;
    border: none;
    border-radius: 8px;
    padding: 0.75rem 1.5rem;
    font-weight: 600;
    cursor: pointer;
    transition: transform 0.2s;
}

/* Grids responsivos */
.stats-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
    gap: 1.5rem;
}
```

## 🔧 Configuración del Sistema

### **📄 `.env` - Variables de Entorno**
```env
APP_NAME="Quibdó Seguro"
APP_ENV=local
APP_KEY=base64:generated_key
APP_DEBUG=true
APP_URL=http://localhost

# Base de datos MongoDB
DB_CONNECTION=mongodb
DB_HOST=127.0.0.1
DB_PORT=27017
DB_DATABASE=quibdo_seguro

# Configuración de sesiones
SESSION_DRIVER=file
SESSION_LIFETIME=120
```

### **📄 `config/database.php` - Configuración de BD**
```php
'mongodb' => [
    'driver' => 'mongodb',
    'host' => env('DB_HOST', '127.0.0.1'),
    'port' => env('DB_PORT', 27017),
    'database' => env('DB_DATABASE', 'quibdo_seguro'),
    'username' => env('DB_USERNAME', ''),
    'password' => env('DB_PASSWORD', ''),
],
```

## 🚀 Optimizaciones Implementadas

### **1. Consultas Eficientes**
```php
// Usar eager loading para evitar N+1 queries
$canjes = Canje::with(['usuario', 'oferta', 'comercio'])->get();

// Usar select específico para campos necesarios
$usuarios = User::select('nombre', 'email', 'rol')->get();
```

### **2. Caching de Vistas**
```php
// Las vistas Blade se compilan y cachean automáticamente
// Para limpiar cache: php artisan view:clear
```

### **3. Optimización de Assets**
```css
/* CSS minificado en producción */
/* Uso de CDN para librerías externas */
/* Compresión de imágenes */
```

## 📱 Responsive Design

### **Breakpoints Definidos**
```css
/* Mobile First Approach */
@media (max-width: 768px) {
    .stats-grid {
        grid-template-columns: 1fr;
    }
    
    .header-nav {
        flex-direction: column;
    }
}

@media (min-width: 769px) and (max-width: 1024px) {
    .stats-grid {
        grid-template-columns: repeat(2, 1fr);
    }
}

@media (min-width: 1025px) {
    .stats-grid {
        grid-template-columns: repeat(4, 1fr);
    }
}
```

## 🔍 Debugging y Logs

### **Sistema de Logs**
```php
// En controladores
use Illuminate\Support\Facades\Log;

Log::info('Usuario creó reporte', [
    'usuario_id' => Auth::id(),
    'incidente_id' => $incidente->id
]);

Log::error('Error al procesar canje', [
    'error' => $e->getMessage(),
    'usuario_id' => Auth::id()
]);
```

### **Manejo de Errores**
```php
// En controladores
try {
    $canje = Canje::create($data);
    return redirect()->back()->with('success', 'Canje realizado');
} catch (\Exception $e) {
    Log::error('Error en canje: ' . $e->getMessage());
    return redirect()->back()->with('error', 'Error al procesar canje');
}
```

## 🎯 Conclusión

Este sistema está diseñado con:

- **✅ Arquitectura MVC clara** - Separación de responsabilidades
- **✅ Código reutilizable** - Componentes y servicios modulares  
- **✅ Seguridad robusta** - Autenticación, autorización y validación
- **✅ Diseño moderno** - Responsive y accesible
- **✅ Base de datos eficiente** - MongoDB con relaciones optimizadas
- **✅ Mantenibilidad** - Código limpio y documentado

Cada archivo tiene un propósito específico y contribuye al funcionamiento integral del sistema de reportes ciudadanos de Quibdó.