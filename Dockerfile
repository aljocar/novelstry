# Etapa de construcción
FROM composer:2 as builder

WORKDIR /app
COPY . .

# 1. Forzar MySQL durante el build y evitar SQLite
RUN echo "DB_CONNECTION=mysql" > .env && \
    echo "DB_HOST=127.0.0.1" >> .env && \
    composer install --no-dev --optimize-autoloader --ignore-platform-reqs && \
    rm .env

# Etapa de producción
FROM php:8.2-fpm-alpine

WORKDIR /var/www/html

# Instalar dependencias
RUN apk add --no-cache \
    nginx \
    supervisor \
    libpng-dev \
    libzip-dev \
    zip \
    unzip \
    && docker-php-ext-install pdo pdo_mysql zip gd

# Configuraciones
COPY docker/nginx.conf /etc/nginx/nginx.conf
COPY docker/supervisord.conf /etc/supervisor/conf.d/supervisord.conf

# Copiar aplicación
COPY --from=builder /app /var/www/html
COPY . .

# Permisos y optimización (sin acceder a DB)
RUN chown -R www-data:www-data storage bootstrap/cache && \
    chmod -R 775 storage bootstrap/cache && \
    php artisan storage:link && \
    php artisan optimize:clear

EXPOSE 8080
CMD ["/usr/bin/supervisord", "-c", "/etc/supervisor/conf.d/supervisord.conf"]