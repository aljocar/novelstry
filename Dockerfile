# Etapa de construcción
FROM composer:2 as builder

WORKDIR /app
COPY . .
RUN composer install --no-dev --optimize-autoloader

# Etapa de producción
FROM php:8.2-fpm-alpine

WORKDIR /var/www/html

# Instalar dependencias del sistema
RUN apk add --no-cache \
    nginx \
    supervisor \
    libpng-dev \
    libzip-dev \
    zip \
    unzip \
    && docker-php-ext-install pdo pdo_mysql zip gd

# Copiar configuración de Nginx y Supervisor
COPY docker/nginx.conf /etc/nginx/nginx.conf
COPY docker/supervisord.conf /etc/supervisor/conf.d/supervisord.conf

# Copiar la aplicación construida
COPY --from=builder /app /var/www/html
COPY . .

# Configurar permisos (sin ejecutar Artisan todavía)
RUN chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache && \
    chmod -R 775 /var/www/html/storage /var/www/html/bootstrap/cache

# Crear directorios necesarios (ej: para imágenes por defecto)
RUN mkdir -p /var/www/html/storage/app/public/defaults && \
    chown -R www-data:www-data /var/www/html/storage/app/public && \
    chmod -R 775 /var/www/html/storage/app/public

# --- Aquí es seguro ejecutar Artisan ---
# Configuración temporal para comandos artisan durante el build
ENV CACHE_DRIVER=array \
    SESSION_DRIVER=array \
    QUEUE_CONNECTION=sync

# Comandos artisan que NO requieren DB
RUN php artisan config:clear && \
    php artisan view:clear

# Opcional: Si realmente necesitas cache:clear, usa esta versión
RUN php artisan cache:clear --no-interaction 2>/dev/null || true

EXPOSE 8080
CMD ["/usr/bin/supervisord", "-c", "/etc/supervisor/conf.d/supervisord.conf"]