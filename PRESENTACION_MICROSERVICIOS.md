# Quibdo Seguro como sistema de microservicios

## Idea central

Quibdo Seguro se presenta como una plataforma web con un cliente Laravel Blade y una capa de microservicios detrás de un API Gateway.

El cliente web corre en `http://localhost:8006` y consume el gateway en `http://localhost:8000/api`. El gateway enruta las peticiones a servicios especializados.

## Servicios

| Servicio | Puerto | Responsabilidad |
| --- | --- | --- |
| Web Client | 8006 | Interfaz web para ciudadanos, comercios, autoridades y admins |
| API Gateway | 8000 | Punto de entrada único, rate limit y enrutamiento |
| Auth Service | 8001 | Registro, login, tokens, usuarios y puntos |
| Incident Service | 8002 | Reportes ciudadanos, incidentes y comentarios |
| Rewards Service | 8003 | Recompensas, ofertas, canjes y validación QR |
| Notification Service | 8004 | Notificaciones a usuarios y autoridades |
| Analytics Service | 8005 | Métricas del dashboard e indicadores |

## Flujo de autenticación

1. El usuario inicia sesión desde el cliente web.
2. El cliente llama al gateway: `POST /api/auth/login`.
3. El gateway reenvía la solicitud al Auth Service.
4. Auth Service valida credenciales y genera un token.
5. El token se usa para consumir operaciones protegidas.

## Flujo de reporte ciudadano

1. El ciudadano crea un reporte con ubicación, tipo, descripción y evidencia.
2. El reporte queda con estado pendiente.
3. Las autoridades ven los reportes pendientes.
4. La autoridad valida o rechaza el reporte.
5. Si se valida, el ciudadano gana puntos y recibe notificación.

## Flujo de recompensas

1. Comercios publican ofertas.
2. Ciudadanos canjean puntos por ofertas.
3. El sistema genera un código de canje.
4. El comercio valida el código.
5. El canje queda marcado como validado.

## Endpoints de salud para la demo

```bash
curl http://localhost:8000/api/health
curl http://localhost:8000/api/health/services
```

El segundo comando muestra si los cinco microservicios están disponibles desde el gateway.

## Comandos de arranque

```bash
bash start-services.sh
bash manage-services.sh status
```

Para detener:

```bash
bash manage-services.sh stop
```

## Usuarios de demo

Después de ejecutar los seeders:

| Rol | Email | Password |
| --- | --- | --- |
| Admin | admin@quibdo.com | admin123 |
| Ciudadano | usuario@quibdo.com | usuario123 |
| Autoridad | autoridad@quibdo.com | autoridad123 |
| Comercio | comercio@quibdo.com | comercio123 |

## Cómo explicar la arquitectura

Quibdo Seguro separa responsabilidades por dominio: autenticación, incidentes, recompensas, notificaciones y analítica. El gateway evita que el cliente conozca la ubicación interna de cada servicio y centraliza la entrada al sistema. Todos los servicios usan MongoDB durante la demo para simplificar instalación local, pero el diseño permite separar bases por servicio en una etapa posterior.

## Límites actuales

- La comunicación entre servicios es HTTP síncrona.
- RabbitMQ está planteado como mejora futura para eventos.
- En demo local se comparte una misma base MongoDB.
- Faltan más pruebas automatizadas para los flujos completos.
