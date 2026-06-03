# Quibdó Seguro - Arquitectura de Microservicios

## Estructura

```
microservices/
├── gateway/               Puerto 8000 - Punto de entrada único
├── auth-service/          Puerto 8001 - Autenticación y usuarios
├── incident-service/      Puerto 8002 - Incidentes y comentarios
├── rewards-service/       Puerto 8003 - Recompensas, ofertas y canjes
├── notification-service/  Puerto 8004 - Notificaciones
└── analytics-service/     Puerto 8005 - Estadísticas y métricas
```

## Flujo de comunicación

```
Frontend (Blade/JS)
       ↓
  API Gateway :8000/api
       ↓
  ┌────┴────────────────────────────────┐
  │                                     │
auth-service   incident-service   rewards-service
  :8001             :8002              :8003
                      ↓                  ↓
              notification-service  (:8004)
                      ↑
              analytics-service (:8005)
```

## Instalación de cada servicio

Para cada servicio en `auth-service`, `incident-service`, `rewards-service`,
`notification-service`, `analytics-service`, `gateway`:

```bash
cd microservices/<nombre-servicio>
cp .env.example .env
composer install
php artisan key:generate
```

## Arrancar los servicios (desarrollo)

Abrir una terminal por servicio:

```bash
# Gateway
php -S localhost:8000 -t microservices/gateway/public

# Auth Service
php -S localhost:8001 -t microservices/auth-service/public

# Incident Service
php -S localhost:8002 -t microservices/incident-service/public

# Rewards Service
php -S localhost:8003 -t microservices/rewards-service/public

# Notification Service
php -S localhost:8004 -t microservices/notification-service/public

# Analytics Service
php -S localhost:8005 -t microservices/analytics-service/public
```

## Endpoints del Gateway (puerto 8000)

### Health
| Método | Endpoint              | Descripción          |
|--------|-----------------------|----------------------|
| GET    | /api/health           | Salud del gateway    |
| GET    | /api/health/services  | Salud de servicios   |

### Auth
| Método | Endpoint              | Descripción          |
|--------|-----------------------|----------------------|
| POST   | /api/auth/login       | Login                |
| POST   | /api/auth/register    | Registro             |
| POST   | /api/auth/logout      | Logout               |
| GET    | /api/auth/profile     | Perfil autenticado   |

### Incidentes
| Método | Endpoint                    | Descripción              |
|--------|-----------------------------|--------------------------|
| GET    | /api/incidentes             | Listar (con filtros)     |
| GET    | /api/incidentes/{id}        | Detalle                  |
| POST   | /api/incidentes             | Crear reporte            |
| POST   | /api/incidentes/comentarios | Agregar comentario       |

### Recompensas
| Método | Endpoint                    | Descripción              |
|--------|-----------------------------|--------------------------|
| GET    | /api/recompensas            | Listar recompensas       |
| POST   | /api/canjear                | Canjear oferta           |
| GET    | /api/canjes                 | Mis canjes               |
| POST   | /api/validar-canje          | Validar QR (comercio)    |
| GET    | /api/estadisticas/comercio  | Stats del comercio       |

### Notificaciones
| Método | Endpoint                    | Descripción              |
|--------|-----------------------------|--------------------------|
| GET    | /api/notificaciones         | Mis notificaciones       |
| PUT    | /api/notificaciones/{id}    | Marcar como leída        |

### Analytics
| Método | Endpoint                    | Descripción              |
|--------|-----------------------------|--------------------------|
| GET    | /api/stats/dashboard        | Stats del dashboard      |
| GET    | /api/stats/incidentes       | Stats de incidentes      |
| GET    | /api/stats/usuarios         | Stats de usuarios        |

## Formato de respuesta estándar

Todos los servicios responden con este formato:

```json
{
  "success": true,
  "message": "Descripción del resultado",
  "data": {}
}
```

## Autenticación entre servicios

1. El cliente envía `Authorization: Bearer <token>` al Gateway.
2. El Gateway reenvía el header al microservicio destino.
3. El microservicio valida el token llamando a `GET /api/auth/profile` en auth-service.
4. Si es válido, inyecta los datos del usuario en el request y continúa.

## Base de datos

Todos los servicios comparten la misma instancia MongoDB:
- URI: `mongodb://127.0.0.1:27017`
- Database: `quibdo_seguro`

Las colecciones son las mismas que usa el monolito actual, por lo que
**no se pierde ningún dato** durante la migración.

## Preparación para Docker (futuro)

Cada servicio tiene su propio `composer.json` y `.env.example`,
lo que facilita crear un `Dockerfile` independiente por servicio
y orquestarlos con `docker-compose`.

## Preparación para RabbitMQ (futuro)

Los eventos ya están definidos en `notification-service/app/Events/`:
- `NuevoReporteEvent`
- `CanjeRealizadoEvent`
- `ReporteValidadoEvent`

Y los listeners en `notification-service/app/Listeners/`:
- `NotificarAutoridadesListener`
- `NotificarUsuarioListener`

Cuando se implemente RabbitMQ, solo hay que cambiar el dispatcher
de eventos de síncrono a asíncrono sin modificar la lógica de negocio.

## Cliente web

El proyecto Laravel principal funciona como cliente web en `http://localhost:8006`.
Para la demo de microservicios, el login y registro del cliente web se comunican con
el API Gateway usando `GATEWAY_URL=http://localhost:8000/api`.
