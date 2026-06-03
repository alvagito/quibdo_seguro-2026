<<<<<<< HEAD
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
=======
# quibdo_seguro 2026



## Getting started

To make it easy for you to get started with GitLab, here's a list of recommended next steps.

Already a pro? Just edit this README.md and make it your own. Want to make it easy? [Use the template at the bottom](#editing-this-readme)!

## Add your files

* [Create](https://docs.gitlab.com/user/project/repository/web_editor/#create-a-file) or [upload](https://docs.gitlab.com/user/project/repository/web_editor/#upload-a-file) files
* [Add files using the command line](https://docs.gitlab.com/topics/git/add_files/#add-files-to-a-git-repository) or push an existing Git repository with the following command:

```
cd existing_repo
git remote add origin https://gitlab.com/alvaro4890708/quibdo_seguro-2026.git
git branch -M main
git push -uf origin main
```

## Integrate with your tools

* [Set up project integrations](https://gitlab.com/alvaro4890708/quibdo_seguro-2026/-/settings/integrations)

## Collaborate with your team

* [Invite team members and collaborators](https://docs.gitlab.com/user/project/members/)
* [Create a new merge request](https://docs.gitlab.com/user/project/merge_requests/creating_merge_requests/)
* [Automatically close issues from merge requests](https://docs.gitlab.com/user/project/issues/managing_issues/#closing-issues-automatically)
* [Enable merge request approvals](https://docs.gitlab.com/user/project/merge_requests/approvals/)
* [Set auto-merge](https://docs.gitlab.com/user/project/merge_requests/auto_merge/)

## Test and Deploy

Use the built-in continuous integration in GitLab.

* [Get started with GitLab CI/CD](https://docs.gitlab.com/ci/quick_start/)
* [Analyze your code for known vulnerabilities with Static Application Security Testing (SAST)](https://docs.gitlab.com/user/application_security/sast/)
* [Deploy to Kubernetes, Amazon EC2, or Amazon ECS using Auto Deploy](https://docs.gitlab.com/topics/autodevops/requirements/)
* [Use pull-based deployments for improved Kubernetes management](https://docs.gitlab.com/user/clusters/agent/)
* [Set up protected environments](https://docs.gitlab.com/ci/environments/protected_environments/)

***

# Editing this README

When you're ready to make this README your own, just edit this file and use the handy template below (or feel free to structure it however you want - this is just a starting point!). Thanks to [makeareadme.com](https://www.makeareadme.com/) for this template.

## Suggestions for a good README

Every project is different, so consider which of these sections apply to yours. The sections used in the template are suggestions for most open source projects. Also keep in mind that while a README can be too long and detailed, too long is better than too short. If you think your README is too long, consider utilizing another form of documentation rather than cutting out information.

## Name
Choose a self-explaining name for your project.

## Description
Let people know what your project can do specifically. Provide context and add a link to any reference visitors might be unfamiliar with. A list of Features or a Background subsection can also be added here. If there are alternatives to your project, this is a good place to list differentiating factors.

## Badges
On some READMEs, you may see small images that convey metadata, such as whether or not all the tests are passing for the project. You can use Shields to add some to your README. Many services also have instructions for adding a badge.

## Visuals
Depending on what you are making, it can be a good idea to include screenshots or even a video (you'll frequently see GIFs rather than actual videos). Tools like ttygif can help, but check out Asciinema for a more sophisticated method.

## Installation
Within a particular ecosystem, there may be a common way of installing things, such as using Yarn, NuGet, or Homebrew. However, consider the possibility that whoever is reading your README is a novice and would like more guidance. Listing specific steps helps remove ambiguity and gets people to using your project as quickly as possible. If it only runs in a specific context like a particular programming language version or operating system or has dependencies that have to be installed manually, also add a Requirements subsection.

## Usage
Use examples liberally, and show the expected output if you can. It's helpful to have inline the smallest example of usage that you can demonstrate, while providing links to more sophisticated examples if they are too long to reasonably include in the README.

## Support
Tell people where they can go to for help. It can be any combination of an issue tracker, a chat room, an email address, etc.

## Roadmap
If you have ideas for releases in the future, it is a good idea to list them in the README.

## Contributing
State if you are open to contributions and what your requirements are for accepting them.

For people who want to make changes to your project, it's helpful to have some documentation on how to get started. Perhaps there is a script that they should run or some environment variables that they need to set. Make these steps explicit. These instructions could also be useful to your future self.

You can also document commands to lint the code or run tests. These steps help to ensure high code quality and reduce the likelihood that the changes inadvertently break something. Having instructions for running tests is especially helpful if it requires external setup, such as starting a Selenium server for testing in a browser.

## Authors and acknowledgment
Show your appreciation to those who have contributed to the project.

## License
For open source projects, say how it is licensed.

## Project status
If you have run out of energy or time for your project, put a note at the top of the README saying that development has slowed down or stopped completely. Someone may choose to fork your project or volunteer to step in as a maintainer or owner, allowing your project to keep going. You can also make an explicit request for maintainers.
>>>>>>> be697654fc0247a103416ed6dfb496bddd7db489
