# academico-web

Repositorio para la aplicación web de gestión académica de la organización academic-mgmt-org.

## Documentacion de arquitectura

La documentacion canonica del flujo de autenticacion y enrutamiento esta versionada en el repositorio `academico-gateway`:

- `academico-gateway/docs/architecture/gateway-auth-routing.md`
- `academico-gateway/docs/adr/0001-gateway-auth-jwt-routing.md`

En Azure DevOps, publicar `academico-gateway/docs` como Wiki del proyecto.

## Rol esperado en el flujo

La aplicacion web debe consumir el gateway como punto de entrada:

- Login: `POST /usuarios/api/v1/auth/login`.
- Requests protegidos: enviar `Authorization: Bearer <accessToken>`.
- No consumir microservicios internos directamente desde el navegador.
