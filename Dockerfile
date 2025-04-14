# Etapa de construcción
FROM composer:2 as builder

WORKDIR /app
COPY . .

# Crea el directorio bootstrap/cache con permisos antes de composer install
RUN mkdir -p /app/bootstrap/cache && \
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

# Permisos (ahora solo para producción)
RUN chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache && \
    chmod -R 775 /var/www/html/storage /var/www/html/bootstrap/cache

# Configuración temporal para el build
ENV CACHE_DRIVER=array \
    SESSION_DRIVER=array \
    QUEUE_CONNECTION=sync

# Comandos artisan seguros
RUN php artisan config:clear && \
    php artisan view:clear && \
    php artisan storage:link

EXPOSE 8080
CMD ["/usr/bin/supervisord", "-c", "/etc/supervisor/conf.d/supervisord.conf"]