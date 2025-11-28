# ERRONKA2

Aplicación PHP para gestión de farmacia (clientes, empleados, productos, pedidos).

## CI

El flujo de integración continua se define en `.github/workflows/ci.yml` y se ejecuta en:

- Cada `push` a la rama `main`.
- Cada `pull_request` hacia `main`.

Pasos principales:

- Checkout del repositorio.
- Configuración de PHP 8.2 con extensiones `mbstring`, `pdo`, `pdo_mysql`.
- Lint de todos los ficheros `*.php` con `php -l`.
- Instalación de dependencias con Composer si existe `composer.json`.

## CD (Deploy)

El flujo de despliegue continuo se define en `.github/workflows/deploy.yml` y se ejecuta en cada `push` a `main`.

El workflow usa `ssh` + `rsync` para desplegar el código a un servidor remoto en la ruta `/var/www/erronka2`.

Debes configurar en los *Repository secrets* de GitHub (`Settings → Secrets and variables → Actions`) los siguientes secretos:

- `SSH_HOST`: dominio o IP del servidor de producción.
- `SSH_USER`: usuario SSH.
- `SSH_PORT`: puerto SSH (por ejemplo `22`).
- `SSH_KEY`: clave privada SSH (contenido del fichero, por ejemplo `~/.ssh/id_rsa`).

Una vez configurado, cada push a `main` lanzará el deploy automático.
