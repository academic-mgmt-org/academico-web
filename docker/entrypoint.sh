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

# Run database migrations for SQLite (create file if not exists)
if [ "${DB_CONNECTION}" = "sqlite" ] || [ -z "${DB_CONNECTION}" ]; then
    DB_DATABASE=$(php -r "echo env('DB_DATABASE', database_path('database.sqlite'));")
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
exec supervisord -c /etc/supervisor/conf.d/supervisord.conf
