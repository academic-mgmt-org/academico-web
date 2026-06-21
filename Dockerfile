# Stage 1: Build Assets
FROM node:20-alpine AS assets-builder
WORKDIR /app
COPY package*.json vite.config.js ./
COPY resources/ ./resources/
COPY public/ ./public/
RUN npm ci || npm install
RUN npm run build

# Stage 2: Application Running Environment (Pure Alpine with precompiled PHP)
FROM alpine:3.20

# Set working directory
WORKDIR /var/www

# Install system dependencies and precompiled PHP 8.3 with extensions (including gRPC and Protobuf)
RUN apk add --no-cache \
    git \
    curl \
    zip \
    unzip \
    nginx \
    supervisor \
    procps \
    php83 \
    php83-fpm \
    php83-pecl-grpc \
    php83-pecl-protobuf \
    php83-pdo \
    php83-pdo_mysql \
    php83-pdo_sqlite \
    php83-mbstring \
    php83-exif \
    php83-pcntl \
    php83-bcmath \
    php83-gd \
    php83-xml \
    php83-zip \
    php83-curl \
    php83-session \
    php83-tokenizer \
    php83-xmlwriter \
    php83-xmlreader \
    php83-dom \
    php83-phar \
    php83-openssl \
    php83-fileinfo \
    php83-simplexml \
    php83-ctype \
    php83-iconv \
    php83-sodium \
    php83-opcache

# Symlink PHP binaries to standard paths
RUN ln -sf /usr/bin/php83 /usr/bin/php \
    && ln -sf /usr/sbin/php-fpm83 /usr/sbin/php-fpm

# Install Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Copy application files
COPY . .

# Copy built assets from assets-builder stage
COPY --from=assets-builder /app/public/build ./public/build

# Install Composer dependencies
RUN composer install --no-dev --optimize-autoloader --no-interaction --ignore-platform-reqs

# Configure Nginx to run as www-data instead of nginx to match PHP-FPM permissions
# (First ensure user www-data exists in Alpine; the shadow package handles it, or we can use the default www-data group/user in alpine)
RUN getent group www-data || addgroup -g 82 -S www-data \
    && getent passwd www-data || adduser -u 82 -D -S -G www-data www-data

RUN sed -i 's/user nginx;/user www-data;/g' /etc/nginx/nginx.conf

# Configure PHP-FPM to run as www-data and listen on port 9000
RUN sed -i 's/listen = .*/listen = 127.0.0.1:9000/g' /etc/php83/php-fpm.d/www.conf \
    && sed -i 's/user = .*/user = www-data/g' /etc/php83/php-fpm.d/www.conf \
    && sed -i 's/group = .*/group = www-data/g' /etc/php83/php-fpm.d/www.conf

# Setup directories permissions
RUN chown -R www-data:www-data /var/www \
    && chmod -R 775 /var/www/storage /var/www/bootstrap/cache

# Copy docker configuration files
COPY docker/nginx.conf /etc/nginx/http.d/default.conf
COPY docker/supervisord.conf /etc/supervisor/conf.d/supervisord.conf
COPY docker/entrypoint.sh /usr/local/bin/entrypoint.sh
RUN chmod +x /usr/local/bin/entrypoint.sh

# Expose ports 80 and 443
EXPOSE 80 443

# Set entrypoint
ENTRYPOINT ["/usr/local/bin/entrypoint.sh"]
