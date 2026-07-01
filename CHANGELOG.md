# Changelog

## [0.2.0](https://github.com/academic-mgmt-org/academico-web/compare/academico-web-v0.1.0...academico-web-v0.2.0) (2026-07-01)


### Features

* add Docker support with multi-stage build, Nginx configuration, and Supervisor process management ([61dfaec](https://github.com/academic-mgmt-org/academico-web/commit/61dfaec261dbd0f1e3feb0074c241011451af995))
* add interactive notification dropdown menu to navigation bar ([ec8e275](https://github.com/academic-mgmt-org/academico-web/commit/ec8e27591d84870b1cd6b4eb4903d51466d6eb8d))
* add restart.sh script to automate Laravel cache clearing and service restarts ([dfbb64c](https://github.com/academic-mgmt-org/academico-web/commit/dfbb64c8967cc8e47e7283dc309985d76b2ffb31))
* add SERVICE_DRIVER configuration and update login documentation for gateway integration ([4e427be](https://github.com/academic-mgmt-org/academico-web/commit/4e427be355b482b39c93daef3c860462280fb41c))
* configure Nginx for SSL/TLS with Let's Encrypt and update Docker port mappings ([ff3298c](https://github.com/academic-mgmt-org/academico-web/commit/ff3298cf6e2b32eccb18a9c0cffdf764cb7da9dc))
* configure nginx reverse proxy for gRPC service endpoints ([695c01d](https://github.com/academic-mgmt-org/academico-web/commit/695c01d64f303444cc221c90012d7ad91e3381b8))
* configure Nginx server block for SSL support on academia-dev.eastus2.cloudapp.azure.com ([bba54b8](https://github.com/academic-mgmt-org/academico-web/commit/bba54b8b43f3df4a71e101028bea5da618d66afb))
* extract user data and permissions from JWT payload during authentication ([e4c28a9](https://github.com/academic-mgmt-org/academico-web/commit/e4c28a9e16781edbcd338f55e19ec4461ad8cfdb))
* implement base frontend structure and login page components with custom CSS and JS services ([3a37f45](https://github.com/academic-mgmt-org/academico-web/commit/3a37f456359f3d9adbcf51df8f2dadb91ad2bc00))
* implement binary decoding in LoginResponse and apply base64 encoding to login password credentials ([0f65544](https://github.com/academic-mgmt-org/academico-web/commit/0f65544365cc4970f42ec90faaaaa97544d8c02d))
* implement CSRF protection for API requests and add configurable gRPC timeout settings ([d6fd579](https://github.com/academic-mgmt-org/academico-web/commit/d6fd5799ac5bd64777704fda51b639a6b92fb6ea))
* implement dynamic Nginx TLS configuration using envsubst for HTTPS support ([175ec22](https://github.com/academic-mgmt-org/academico-web/commit/175ec225f86a097c8ec301230161d168d72d55eb))
* implement login page UI and core frontend architecture including authentication services and global styles ([3144e42](https://github.com/academic-mgmt-org/academico-web/commit/3144e42a03e74be14f06046b9da4651be5ea70f0))
* implement password recovery functionality via gRPC service and UI integration ([e68982f](https://github.com/academic-mgmt-org/academico-web/commit/e68982f07aa1f50b9c7a2b8ef49be8d6e5e410e8))
* implement server-side session management and role-based access control for student authentication ([4a28f51](https://github.com/academic-mgmt-org/academico-web/commit/4a28f5108cf8691dc568598353609ee2736f90b1))
* implement session verification via refresh token on login page load ([52e3e96](https://github.com/academic-mgmt-org/academico-web/commit/52e3e968130b8d588fccc255db88f4a4f9bdf805))
* implement student dashboard interface and add associated layout and component styles ([d73c130](https://github.com/academic-mgmt-org/academico-web/commit/d73c1301d84ab4abf22409f024b67f90232ffedb))
* include userName in AuthGrpcService payload and update student view to prioritize it for name display ([80bd211](https://github.com/academic-mgmt-org/academico-web/commit/80bd21151522f0d00d2d3f9a9cf7fcfcd5f8b86e))
* initialize frontend architecture with base styles, service layer, and login page functionality ([3667938](https://github.com/academic-mgmt-org/academico-web/commit/3667938c58bb4c8e5e0a586b06ad84e980e8fcaf))
* initialize new Laravel project with baseline configuration and structure ([325231d](https://github.com/academic-mgmt-org/academico-web/commit/325231d9a17f42603fccee885e5c5eae9571c085))
* install gRPC PHP extension in Dockerfile ([dd6354a](https://github.com/academic-mgmt-org/academico-web/commit/dd6354a13ab121db5364219030ad80a14acacd6a))
* integrate gRPC authentication service with login page and API endpoint ([9880db4](https://github.com/academic-mgmt-org/academico-web/commit/9880db4e479efa978ee6a25dd5d1b6fb631b60a2))
* integrate NotificationGatewayService to fetch and display dynamic student notifications ([a58ac8e](https://github.com/academic-mgmt-org/academico-web/commit/a58ac8e72e2cb9379af37db3d81a6886dcdbe9df))


### Bug Fixes

* ensure supervisor log directory exists before starting service ([700844e](https://github.com/academic-mgmt-org/academico-web/commit/700844ed76ecb5623626a39c10e50e0a5ddef248))
* include nginx config in docker build ([6e58b9c](https://github.com/academic-mgmt-org/academico-web/commit/6e58b9c2b4098e8f02944db7f9bf7d3f385de4e1))
* set correct permissions for storage, cache, and database directories in entrypoint script ([71d31b9](https://github.com/academic-mgmt-org/academico-web/commit/71d31b9e33d0d3d2cb1ae82ed26f27e19b52e40f))
* update docker-compose port mapping to expose container port 443 on host port 443 ([de52236](https://github.com/academic-mgmt-org/academico-web/commit/de52236668811f1a60bb89c9b310d86a13945c07))
* update SQLite database path resolution to use artisan config command ([04f8df7](https://github.com/academic-mgmt-org/academico-web/commit/04f8df7a735fa5fd20f59ac096ca49c616fdbc50))
