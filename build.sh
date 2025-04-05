#!/bin/bash
composer install --no-dev --optimize-autoloader
npm install && npm run prod
php artisan key:generate --force
php artisan migrate --force
php artisan config:cache
php artisan route:cache
php artisan view:cache
chmod 777 -R storage/
chmod 777 -R bootstrap/cache
php artisan storage:link