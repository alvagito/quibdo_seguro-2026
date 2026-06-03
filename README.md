# 🏛️ Quibdó Seguro

Sistema integral de reportes ciudadanos y gestión de seguridad para la ciudad de Quibdó, Chocó.

## 📋 Descripción

Quibdó Seguro es una plataforma web que permite a los ciudadanos reportar incidentes de seguridad, participar en un sistema de recompensas por su colaboración, y facilita la gestión administrativa de estos reportes.

## ✨ Características Principales

### 👥 **Para Ciudadanos**
- 📱 **Reportar Incidentes**: Sistema intuitivo para reportar problemas de seguridad
- 🗺️ **Mapa Interactivo**: Visualización de incidentes en tiempo real
- 🏆 **Sistema de Puntos**: Gana puntos por reportar incidentes
- 🎁 **Recompensas**: Canjea puntos por ofertas de comercios locales
- 📊 **Dashboard Personal**: Seguimiento de tus reportes y estadísticas

### 🏪 **Para Comercios Aliados**
- 💼 **Panel de Gestión**: Administra tus ofertas y promociones
- 🎯 **Crear Ofertas**: Publica ofertas para atraer clientes
- 📈 **Estadísticas**: Monitorea el rendimiento de tus ofertas
- ✅ **Validación de Canjes**: Sistema de códigos QR para validar canjes

### 👨‍💼 **Para Administradores**
- 🔧 **Gestión Completa**: Administra usuarios, reportes y comercios
- 📊 **Dashboard Administrativo**: Métricas y estadísticas del sistema
- 🏪 **Gestión de Ofertas**: Modera las ofertas de comercios
- 🎁 **Recompensas del Sistema**: Crea recompensas especiales

## 🛠️ Tecnologías Utilizadas

- **Backend**: PHP 8+ con Laravel Framework
- **Base de Datos**: MongoDB
- **Frontend**: HTML5, CSS3, JavaScript (Vanilla)
- **Estilos**: CSS moderno con gradientes y animaciones
- **Iconos**: Font Awesome 6
- **Mapas**: Leaflet.js con OpenStreetMap

## 🚀 Instalación

### Prerrequisitos
- PHP 8.0 o superior
- Composer
- MongoDB
- Servidor web (Apache/Nginx)

### Pasos de Instalación

1. **Clonar el repositorio**
   ```bash
   git clone https://gitlab.com/alvarochaverra0510/quibdo_seguro_final.git
   cd quibdo_seguro_final
   ```

2. **Instalar dependencias**
   ```bash
   composer install
   ```

3. **Configurar el entorno**
   ```bash
   cp .env.example .env
   ```
   
4. **Configurar la base de datos MongoDB en `.env`**
   ```env
   DB_CONNECTION=mongodb
   DB_HOST=127.0.0.1
   DB_PORT=27017
   DB_DATABASE=quibdo_seguro
   ```

5. **Generar clave de aplicación**
   ```bash
   php artisan key:generate
   ```

6. **Ejecutar seeders (opcional)**
   ```bash
   php artisan db:seed --class=MongoDBSeeder
   php artisan db:seed --class=IncidentesSeeder
   ```

7. **Iniciar el servidor**
   ```bash
   php artisan serve
   ```

## 📱 Uso del Sistema

### **Registro de Usuarios**
- Los ciudadanos se registran con rol "normal"
- Los comercios se registran con rol "comercio"
- Los administradores son asignados manualmente

### **Flujo de Reportes**
1. Usuario reporta un incidente
2. Gana puntos por el reporte
3. El reporte aparece en el mapa público
4. Administradores pueden gestionar el reporte

### **Sistema de Recompensas**
1. Comercios crean ofertas
2. Usuarios canjean puntos por ofertas
3. Reciben código QR
4. Comercios validan el código
5. Canje completado

## 🎨 Diseño y UX

- **Diseño Moderno**: Interfaz limpia con gradientes y animaciones
- **Responsive**: Optimizado para móviles y escritorio
- **Accesible**: Cumple con estándares de accesibilidad
- **Intuitivo**: Navegación clara y procesos simples

## 📊 Características Técnicas

- **Arquitectura MVC**: Código organizado y mantenible
- **Seguridad**: Autenticación y autorización robusta
- **Performance**: Consultas optimizadas y caching
- **Escalabilidad**: Diseñado para crecer con la ciudad

## 🤝 Contribuir

1. Fork el proyecto
2. Crea una rama para tu feature (`git checkout -b feature/AmazingFeature`)
3. Commit tus cambios (`git commit -m 'Add some AmazingFeature'`)
4. Push a la rama (`git push origin feature/AmazingFeature`)
5. Abre un Pull Request

## 📄 Licencia

Este proyecto está bajo la Licencia MIT - ver el archivo [LICENSE](LICENSE) para detalles.

## 👥 Equipo

Desarrollado para la ciudad de Quibdó, Chocó - Colombia

## 📞 Soporte

Para soporte técnico o consultas, contacta al equipo de desarrollo.

---

**Quibdó Seguro** - Construyendo una ciudad más segura juntos 🏛️✨