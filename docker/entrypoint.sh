#!/bin/sh
set -e

echo "==> Setting up Laravel environment..."

# Ensure database directory and sqlite file exist
mkdir -p /app/database
touch /app/database/database.sqlite

# Ensure storage directories exist
mkdir -p /app/storage/framework/cache/data \
         /app/storage/framework/sessions \
         /app/storage/framework/views \
         /app/storage/logs \
         /app/bootstrap/cache

# Fix permissions
chown -R www-data:www-data /app/storage /app/bootstrap/cache /app/database
chmod -R 775 /app/storage /app/bootstrap/cache /app/database

# Run migrations and seeders
echo "==> Running database migrations and seeders..."
php /app/artisan migrate --force || php /app/artisan migrate:fresh --force
php /app/artisan db:seed --force || true

# Clear and optimize configuration cache
php /app/artisan config:clear || true
php /app/artisan route:clear || true
php /app/artisan view:clear || true
php /app/artisan cache:clear || true

echo "==> Starting PHP-FPM daemon..."
php-fpm -D

echo "==> Starting Nginx on port 80..."
exec nginx -g "daemon off;"
