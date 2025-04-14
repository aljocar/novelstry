# Etapa de construcción
FROM composer:2 as builder

WORKDIR /app
COPY . .

# Asegura que existan los directorios críticos
RUN mkdir -p /app/bootstrap/cache && \
    mkdir -p /app/resources/views && \
    chmod -R 775 /app/bootstrap/cache && \
    composer install --no-dev --optimize-autoloader

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
COPY --from=builder /app /var/www/html

# Crear estructura de directorios esencial
RUN mkdir -p /var/www/html/storage/framework/views && \
    mkdir -p /var/www/html/resources/views && \
    touch /var/www/html/resources/views/.keep && \
    touch /var/www/html/storage/framework/views/.keep

# Permisos
RUN chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache && \
    chmod -R 775 /var/www/html/storage /var/www/html/bootstrap/cache

# Configuración temporal para el build
ENV CACHE_DRIVER=array \
    SESSION_DRIVER=array \
    QUEUE_CONNECTION=sync

# Comandos artisan seguros (simplificados)
RUN php artisan storage:link

EXPOSE 8080
CMD ["/usr/bin/supervisord", "-c", "/etc/supervisor/conf.d/supervisord.conf"]