# Sitio Cero (Entorno limpio)

Este repositorio usa un entorno independiente para `sitio-cero` en:

- `projects/sitio-cero/`

El entorno tiene su propia configuracion, base de datos y volumenes.

## Estructura relevante

- `projects/sitio-cero/.env`
- `projects/sitio-cero/docker-compose.yml`
- `wp-content/themes/sitio-cero/`

## Variables del entorno sitio-cero

Archivo: `projects/sitio-cero/.env`

- `COMPOSE_PROJECT_NAME=sitio_cero`
- `WP_PORT=8080`
- `MYSQL_DATABASE=wordpress_sitio`
- `MYSQL_USER=wp_sitio`
- `MYSQL_PASSWORD=wp_sitio_pass`
- `MYSQL_ROOT_PASSWORD=root_sitio_pass`

## Levantar entorno

```bash
cd projects/sitio-cero
docker compose up -d
```

Abrir en navegador:

- [http://localhost:8080](http://localhost:8080)

## Limpieza total del entorno sitio-cero

Si quieres dejarlo sin rastros previos (DB + archivos WordPress del volumen):

```bash
cd projects/sitio-cero
docker compose down -v
```

Luego lo levantas de nuevo:

```bash
docker compose up -d
```

## Activar tema

En WordPress:

1. `Apariencia > Temas`
2. Activar `Sitio Cero`
