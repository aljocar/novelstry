# Etapa de construcción
FROM composer:2 as builder

WORKDIR /app
COPY . .

# Instalar dependencias y optimizar
RUN composer install --no-dev --optimize-autoloader && \
    php artisan optimize:clear

# Instalar Node.js y dependencias frontend
FROM node:18 as node_builder
WORKDIR /app
COPY --from=builder /app /app
RUN npm install && npm run build

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
    nodejs \
    npm \
    && docker-php-ext-install pdo pdo_mysql zip gd

# Copiar configuración
COPY docker/nginx.conf /etc/nginx/nginx.conf
COPY docker/supervisord.conf /etc/supervisor/conf.d/supervisord.conf

# Copiar la aplicación construida
COPY --from=builder /app /var/www/html
COPY --from=node_builder /app/public /var/www/html/public

# Configurar permisos y ejecutar comandos finales
RUN chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache && \
    chmod -R 777 storage bootstrap/cache && \
    php artisan storage:link && \
    php artisan optimize

# Variables de entorno para la ejecución
ENV NIXPACKS_BUILD_CMD="composer install && npm i && npm run build && php artisan migrate --force && php artisan optimize && chmod -R 777 storage bootstrap/cache && php artisan storage:link"

EXPOSE 8080
CMD ["/usr/bin/supervisord", "-c", "/etc/supervisor/conf.d/supervisord.conf"]