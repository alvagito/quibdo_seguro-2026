# 🚀 Guía de Ejecución de Microservicios - Quibdo Seguro

Tu proyecto ya está totalmente configurado con una **arquitectura de microservicios completa**. Esta guía te explica cómo levantarlos y verificar que funcionen correctamente.

## ✅ Configuración Actual

| Servicio | Puerto | Descripción |
|----------|--------|-------------|
| **Gateway** | 8000 | Punto de entrada, enruta solicitudes a otros servicios |
| **Auth Service** | 8001 | Autenticación, usuarios, puntos |
| **Incident Service** | 8002 | Gestión de reportes/incidentes |
| **Rewards Service** | 8003 | Recompensas y canjes |
| **Notification Service** | 8004 | Notificaciones |
| **Analytics Service** | 8005 | Métricas y dashboard |

**Base de datos:** MongoDB en `mongodb://127.0.0.1:27017`

## 🔧 Requisitos Previos

1. **PHP 8.1+** instalado
   ```bash
   php --version
   ```

2. **Composer** instalado
   ```bash
   composer --version
   ```

3. **MongoDB** corriendo
   ```bash
   # Ubuntu/Debian
   sudo apt-get install -y mongodb
   sudo service mongodb start
   
   # macOS
   brew install mongodb-community
   brew services start mongodb-community
   
   # O ejecutar MongoDB en Docker
   docker run -d -p 27017:27017 mongo
   ```

## 🎯 Cómo Levantar los Servicios

### Opción 1: Script Automático (Recomendado)

```bash
cd /home/alvaro/Descargas/quibdo_seguro

# Hacer el script ejecutable
chmod +x start-services.sh manage-services.sh

# Iniciar todos los servicios
bash start-services.sh
```

El script hará automáticamente:
- ✅ Verificar MongoDB
- ✅ Ejecutar migraciones en cada servicio
- ✅ Levantar cada servicio en su puerto
- ✅ Crear logs en `/tmp/quibdo-services/`

### Opción 2: Manual (Uno por uno)

Abre **5 terminales diferentes** y ejecuta en cada una:

**Terminal 1 - Auth Service (Puerto 8001):**
```bash
cd /home/alvaro/Descargas/quibdo_seguro/microservices/auth-service
php artisan serve --port=8001
```

**Terminal 2 - Incident Service (Puerto 8002):**
```bash
cd /home/alvaro/Descargas/quibdo_seguro/microservices/incident-service
php artisan serve --port=8002
```

**Terminal 3 - Rewards Service (Puerto 8003):**
```bash
cd /home/alvaro/Descargas/quibdo_seguro/microservices/rewards-service
php artisan serve --port=8003
```

**Terminal 4 - Notification Service (Puerto 8004):**
```bash
cd /home/alvaro/Descargas/quibdo_seguro/microservices/notification-service
php artisan serve --port=8004
```

**Terminal 5 - Analytics Service (Puerto 8005):**
```bash
cd /home/alvaro/Descargas/quibdo_seguro/microservices/analytics-service
php artisan serve --port=8005
```

**Terminal 6 - Gateway (Puerto 8000):**
```bash
cd /home/alvaro/Descargas/quibdo_seguro/microservices/gateway
php artisan serve --port=8000
```

## 📊 Gestionar Servicios

Una vez que los servicios están corriendo, usa el script de gestión:

```bash
# Ver estado de todos los servicios
bash manage-services.sh status

# Detener todos los servicios
bash manage-services.sh stop

# Reiniciar todos los servicios
bash manage-services.sh restart

# Ver logs de todos los servicios
bash manage-services.sh logs

# Ver logs de un servicio específico
bash manage-services.sh logs gateway
bash manage-services.sh logs auth-service
bash manage-services.sh logs incident-service
```

## 🧪 Pruebas Rápidas

### 1. Registrar un usuario
```bash
curl -X POST http://localhost:8000/auth/register \
  -H "Content-Type: application/json" \
  -d '{
    "nombre": "Test User",
    "email": "test@example.com",
    "password": "password123"
  }'
```

### 2. Login
```bash
curl -X POST http://localhost:8000/auth/login \
  -H "Content-Type: application/json" \
  -d '{
    "email": "test@example.com",
    "password": "password123"
  }'
```

Copiar el token del response `access_token` para usar en las siguientes peticiones.

### 3. Obtener perfil
```bash
curl -X GET http://localhost:8000/auth/profile \
  -H "Authorization: Bearer YOUR_TOKEN_HERE"
```

### 4. Crear un incidente (reporte)
```bash
curl -X POST http://localhost:8000/incidentes \
  -H "Authorization: Bearer YOUR_TOKEN_HERE" \
  -F "titulo=Robo en calle principal" \
  -F "descripcion=Se reporta robo de motocicleta" \
  -F "latitud=-2.4" \
  -F "longitud=-76.5" \
  -F "evidencia_foto=@/ruta/a/foto.jpg"
```

### 5. Obtener métricas (Analytics)
```bash
curl -X GET http://localhost:8000/stats \
  -H "Authorization: Bearer YOUR_TOKEN_HERE"
```

## 📁 Logs

Los logs se guardan en: `/tmp/quibdo-services/`

```bash
# Ver logs del gateway en tiempo real
tail -f /tmp/quibdo-services/gateway.log

# Ver últimas 50 líneas del log de auth-service
tail -50 /tmp/quibdo-services/auth-service.log

# Ver todos los errores en los logs
grep "ERROR" /tmp/quibdo-services/*.log
```

## 🐛 Troubleshooting

### Error: "Address already in use" (Puerto ocupado)
```bash
# Matar todos los procesos PHP
killall php

# O identificar qué está usando el puerto
lsof -i :8000
lsof -i :8001
```

### Error: "MongoDB connection failed"
```bash
# Verificar si MongoDB está corriendo
ps aux | grep mongod

# Reiniciar MongoDB
sudo service mongodb restart
# O si está en Docker:
docker ps | grep mongo
```

### Error: "Class not found" o problemas de autoload
```bash
# Regenerar autoloader de composer en cada servicio
cd microservices/auth-service && composer dump-autoload
cd ../incident-service && composer dump-autoload
# ... y así para los demás
```

## 📚 Estructura de Servicios

Cada servicio es un proyecto Laravel independiente con:
- Controladores en `app/Http/Controllers/`
- Servicios de negocio en `app/Services/`
- Modelos MongoDB en `app/Models/`
- Rutas en `routes/api.php`
- Configuración en `.env`

## 🔄 Comunicación Entre Servicios

Los servicios se comunican entre sí a través de HTTP:

- **Incident Service** → **Auth Service**: Obtiene usuarios por rol, actualiza puntos
- **Incident Service** → **Notification Service**: Notifica nuevos reportes
- **Rewards Service** → **Auth Service**: Actualiza puntos
- **Rewards Service** → **Notification Service**: Notifica canjes realizados

Esto está preconfigigurado en los `.env` de cada servicio.

## 🚀 Próximos Pasos

1. ✅ **Levantar los servicios** con `bash start-services.sh`
2. ✅ **Probar endpoints** con los ejemplos curl
3. ✅ **Revisar logs** si hay errores
4. ⏳ Implementar RabbitMQ para eventos asíncronos (preparado pero no implementado)
5. ⏳ Agregar más seguridad (token entre servicios internos)

---

**¿Necesitas ayuda?** Ejecuta los servicios y revisa los logs en `/tmp/quibdo-services/`
