#!/bin/sh
set -e

# Copy env if not exists (in case user mounts volume or needs default)
if [ ! -f .env ]; then
    if [ -f .env.example ]; then
        echo "Creating .env from .env.example"
        cp .env.example .env
    else
        echo "Warning: No .env or .env.example found!"
    fi
fi

: "${NGINX_SERVER_NAME:=localhost}"
: "${NGINX_SSL_CERTIFICATE:=/etc/letsencrypt/live/${NGINX_SERVER_NAME}/fullchain.pem}"
: "${NGINX_SSL_CERTIFICATE_KEY:=/etc/letsencrypt/live/${NGINX_SERVER_NAME}/privkey.pem}"
export NGINX_SERVER_NAME NGINX_SSL_CERTIFICATE NGINX_SSL_CERTIFICATE_KEY

echo "Rendering Nginx configuration for ${NGINX_SERVER_NAME}..."
envsubst '${NGINX_SERVER_NAME} ${NGINX_SSL_CERTIFICATE} ${NGINX_SSL_CERTIFICATE_KEY}' \
    < /etc/nginx/templates/default.conf.template \
    > /etc/nginx/http.d/default.conf

if [ ! -f "$NGINX_SSL_CERTIFICATE" ] || [ ! -f "$NGINX_SSL_CERTIFICATE_KEY" ]; then
    echo "Missing TLS certificate files for ${NGINX_SERVER_NAME}:"
    echo "  certificate: ${NGINX_SSL_CERTIFICATE}"
    echo "  key: ${NGINX_SSL_CERTIFICATE_KEY}"
    exit 1
fi

# Run database migrations for SQLite (create file if not exists)
if [ "${DB_CONNECTION}" = "sqlite" ] || [ -z "${DB_CONNECTION}" ]; then
    DB_DATABASE=$(php artisan tinker --execute="echo config('database.connections.sqlite.database');")
    if [ ! -f "$DB_DATABASE" ]; then
        echo "Creating SQLite database file at $DB_DATABASE"
        mkdir -p "$(dirname "$DB_DATABASE")"
        touch "$DB_DATABASE"
        chown www-data:www-data "$DB_DATABASE"
        chmod 664 "$DB_DATABASE"
    fi
fi

echo "Running migrations..."
php artisan migrate --force

echo "Caching configuration..."
php artisan config:cache
php artisan route:cache
php artisan view:cache

echo "Starting Supervisor..."
mkdir -p /var/log/supervisor
chown -R www-data:www-data /var/www/bootstrap/cache /var/www/storage /var/www/database
exec supervisord -c /etc/supervisor/conf.d/supervisord.conf
