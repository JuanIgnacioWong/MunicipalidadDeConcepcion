# Sitio WordPress desde cero

Base de proyecto para levantar WordPress en local con Docker y un tema personalizado llamado `sitio-cero`.

## Requisitos

- Docker Desktop (o Docker Engine + Docker Compose)

## Arranque rapido

1. Levanta servicios:

```bash
docker compose up -d
```

2. Abre WordPress:

- [http://localhost:8080](http://localhost:8080)

3. Completa el instalador de WordPress (idioma, usuario admin, etc.).

4. Activa el tema:

- Ve a `Apariencia > Temas`
- Activa `Sitio Cero`

## Estructura

- `docker-compose.yml`: servicios WordPress + MySQL.
- `wp-content/themes/sitio-cero/`: tema personalizado.
- `wp-content/themes/sitio-cero/front-page.php`: portada inicial.
- `wp-content/themes/sitio-cero/assets/css/main.css`: estilos principales.
- `wp-content/themes/sitio-cero/assets/js/main.js`: menu movil.

## Personalizacion inicial recomendada

1. Crea un menu en `Apariencia > Menus` y asignalo a `Menu principal`.
2. Crea paginas clave (`Inicio`, `Servicios`, `Contacto`).
3. En `Ajustes > Lectura`, configura una pagina estatica para inicio si quieres reemplazar la portada por una pagina editable.

## Hero con Laminas Hero

La portada usa el post type `Laminas Hero`. Cada registro es una lamina del slider.

1. Ve a `Laminas Hero > Agregar nueva`.
2. En cada lamina define:
   - `Titulo`: texto principal del slide.
   - `Extracto`: bajada del slide.
   - `Imagen destacada`: imagen limpia del slide (sin filtros).
3. En `Opciones de la lamina` puedes configurar:
   - `Texto del boton principal`.
   - `URL del boton principal`.
4. Orden de slides: usa el campo `Orden` de cada lamina.
5. En `Laminas Hero > Todas las laminas`, usa la accion `Clonar` para duplicar una lamina existente.

## Detener entorno

```bash
docker compose down
```
