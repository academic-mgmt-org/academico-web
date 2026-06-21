# Stage 1: Build Assets
FROM node:20-alpine AS assets-builder
WORKDIR /app
COPY package*.json vite.config.js ./
COPY resources/ ./resources/
COPY public/ ./public/
RUN npm ci || npm install
RUN npm run build

# Stage 2: Application Running Environment
FROM php:8.3-fpm-bullseye

# Set working directory
WORKDIR /var/www

# Install system dependencies
RUN apt-get update && apt-get install -y \
    git \
    curl \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    zip \
    unzip \
    nginx \
    supervisor \
    libsqlite3-dev \
    libzip-dev \
    procps \
    && rm -rf /var/lib/apt/lists/*

# Install PHP extensions
RUN docker-php-ext-install pdo pdo_mysql pdo_sqlite mbstring exif pcntl bcmath gd xml zip

# Install gRPC PHP C extension using mlocati installer
COPY --from=mlocati/php-extension-installer /usr/bin/install-php-extensions /usr/local/bin/
RUN install-php-extensions grpc

# Install Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Copy application files
COPY . .

# Copy built assets from assets-builder stage
COPY --from=assets-builder /app/public/build ./public/build

# Install Composer dependencies
# Note: we use --ignore-platform-reqs because the composer.json requires packages like grpc/grpc 
# and google/protobuf which are best run with binary extensions, but we can fall back to pure PHP 
# implementation to keep docker build times fast and portable.
RUN composer install --no-dev --optimize-autoloader --no-interaction --ignore-platform-reqs

# Setup directories permissions
RUN chown -R www-data:www-data /var/www \
    && chmod -R 775 /var/www/storage /var/www/bootstrap/cache

# Copy docker configuration files
COPY docker/nginx.conf /etc/nginx/sites-available/default
COPY docker/supervisord.conf /etc/supervisor/conf.d/supervisord.conf
COPY docker/entrypoint.sh /usr/local/bin/entrypoint.sh
RUN chmod +x /usr/local/bin/entrypoint.sh

# Expose port 80
EXPOSE 80

# Set entrypoint
ENTRYPOINT ["/usr/local/bin/entrypoint.sh"]
