# academico-web

Aplicacion web de gestion academica de la organizacion `academic-mgmt-org`.

El proyecto esta construido sobre Laravel y consume los servicios academicos a traves del API Gateway.

## Documentacion de arquitectura

La documentacion canonica del flujo de autenticacion y enrutamiento esta versionada en el repositorio `academico-gateway`:

- `academico-gateway/docs/architecture/gateway-auth-routing.md`
- `academico-gateway/docs/adr/0001-gateway-auth-jwt-routing.md`

En Azure DevOps, publicar `academico-gateway/docs` como Wiki del proyecto.

## Rol en el flujo

La aplicacion web debe consumir el gateway como punto de entrada:

- Login HTTP publicado por gateway: `POST /login/api/v1/auth/login`.
- Login implementado en esta web: `POST /api/auth/login`, que delega en el servicio configurado por `SERVICE_DRIVER`.
- Requests protegidos: enviar `Authorization: Bearer <accessToken>`.
- No consumir microservicios internos directamente desde el navegador.

## Requisitos

- PHP 8.3 o superior.
- Composer.
- Node.js y npm.
- Extension gRPC de PHP cuando `SERVICE_DRIVER=grpc`.
- Docker, si se usa el entorno containerizado.

## Configuracion

Crear el archivo `.env` desde el ejemplo:

```bash
cp .env.example .env
php artisan key:generate
```

Variables relevantes:

- `SERVICE_DRIVER`: `mock` para desarrollo local sin gRPC, `grpc` para integracion real.
- `SERVICE_GRPC_HOST`: host y puerto del servicio gRPC configurado.
- `APP_URL`: URL publica de la aplicacion.
- `NGINX_SERVER_NAME`: dominio publico usado por Nginx para HTTPS.
- `NGINX_SSL_CERTIFICATE` y `NGINX_SSL_CERTIFICATE_KEY`: rutas opcionales de certificado TLS. Si no se configuran, se usan las rutas de Let's Encrypt para `NGINX_SERVER_NAME`.
- `DB_CONNECTION`: por defecto `sqlite`.

## Ejecucion local

```bash
composer install
npm install
php artisan migrate
npm run dev
php artisan serve
```

Tambien se puede usar el script integrado de Composer:

```bash
composer run dev
```

## Docker

```bash
docker compose up -d --build
```

El contenedor expone los puertos `80` y `443` y monta `.env`, `database/` y `storage/` desde el host.
Para HTTPS, monta `/etc/letsencrypt` desde el host y configura `NGINX_SERVER_NAME` con el dominio del ambiente.

## Imagen Docker

El pipeline de Azure DevOps publica la imagen en Azure Container Registry con el tag del build y con `latest`:

```bash
docker pull acracademicoutn.azurecr.io/academicmgmtorgacademicoweb:latest
```

## Pruebas

```bash
composer test
```
