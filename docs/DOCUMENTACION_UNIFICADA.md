% Documentación Unificada — Quibdó Seguro

Esta documentación unifica los archivos existentes y organiza el material para entrega: documentación técnica, manual de usuario (versión simple), y presentación en LaTeX.

## Resumen ejecutivo
- Proyecto: Quibdó Seguro — plataforma de reportes ciudadanos y gestión de recompensas.
- Arquitectura: Monolito UI (Laravel) + Microservicios (Auth, Incident, Rewards, Notification, Analytics) con API Gateway.
- BBDD principal en servicios: MongoDB.

## Objetivos de la entrega
- Documentación técnica organizada y estética.
- Presentación (diapositivas) en LaTeX (Beamer).
- Manual de usuario en LaTeX, escrito claramente para usuarios de 84 años.
- Instrucciones para desplegar en dominio gratuito y ejecutar el proyecto.

## Estructura de la documentación
- `docs/manual_usuario.tex` — Manual de usuario en LaTeX (legible, con tipografía grande y pasos guiados).
- `docs/manual_tecnico.tex` — Manual técnico (arquitectura, endpoints, variables, despliegue).
- `docs/presentation.tex` — Presentación en Beamer para la entrega.
- `docs/DOCUMENTACION_UNIFICADA.md` — Este archivo (resumen y guía rápida).

## Checklist de requisitos (según la especificación del curso)
1. Estética y orden — se ha organizado en `docs/` con plantillas LaTeX y Markdown.
2. Alojar en dominio gratuito — opciones descritas en `docs/manual_tecnico.tex` (GitHub Pages para docs estáticos; Render/Railway para app).
3. Proyecto funcionando — instrucciones para levantar servicios (ver `MICROSERVICIOS_GUIA.md`) y ejemplo `docker-compose` incluido.
4. Colores y diseño responsivo — el frontend usa variables CSS en `public/assets/css/styles.css`.
5. Necesidad regional — enfoque en reporte ciudadano y recompensas sin exclusión.
6. Documentación y materiales LaTeX — incluidos en `docs/`.

## Cómo usar esta carpeta
1. Revisar `docs/manual_tecnico.tex` para detalles de arquitectura y despliegue.
2. Compilar LaTeX (ver abajo) para generar PDF de presentación y manuales.
3. Seguir `MICROSERVICIOS_GUIA.md` o `start-services.sh` para levantar localmente.

### Compilar LaTeX (local)
Instala TexLive o MikTeX, luego en la carpeta `docs/`:
```bash
pdflatex presentation.tex
pdflatex manual_usuario.tex
pdflatex manual_tecnico.tex
```

### Siguientes pasos sugeridos
- Revisar manual de usuario y ajustar lenguaje si quieres un tono aún más simple.
- Decidir proveedor de hosting gratuito y preparar guía paso a paso para deploy.

---
Documento generado automáticamente a partir de los archivos del repositorio.
