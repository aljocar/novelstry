# Etapa de construcción
FROM composer:2 as builder

WORKDIR /app
COPY . .
RUN composer install --no-dev --optimize-autoloader

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

# Permisos
RUN chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache && \
    chmod -R 775 /var/www/html/storage /var/www/html/bootstrap/cache

# Configuración temporal para el build
ENV CACHE_DRIVER=array \
    SESSION_DRIVER=array \
    QUEUE_CONNECTION=sync

# Configuración de variables de entorno
ENV IMGUR_CLIENT_ID=495d931367a71e3 \
    IMGUR_CLIENT_SECRET=abfa00ca67ccb449897fd9ae74e4701a72fec04e

# Comandos artisan seguros
RUN php artisan config:clear && \
    php artisan view:clear && \
    php artisan storage:link

# Limpieza opcional (ignora errores)
RUN php artisan cache:clear --no-interaction 2>/dev/null || true

EXPOSE 8080
CMD ["/usr/bin/supervisord", "-c", "/etc/supervisor/conf.d/supervisord.conf"]