FROM php:8.2-fpm-alpine

# Install system dependencies
RUN apk add --no-cache \
    nginx \
    curl \
    nodejs \
    npm \
    sqlite

# Install PHP extension installer helper & extensions
COPY --from=mlocati/php-extension-installer /usr/bin/install-php-extensions /usr/local/bin/
RUN install-php-extensions \
    pdo_mysql \
    pdo_sqlite \
    pdo_pgsql \
    bcmath \
    gd \
    intl \
    mbstring \
    opcache \
    zip

# Copy Composer
COPY --from=composer:2.7 /usr/bin/composer /usr/bin/composer

WORKDIR /app

# Copy project files
COPY . /app

# Install Composer dependencies (including require-dev to avoid missing Scribe/AdminLTE classes in Blade)
RUN composer install --optimize-autoloader --no-interaction --ignore-platform-reqs

# Install npm dependencies & build assets
RUN npm ci && npm run build

# Create directories and set permissions
RUN mkdir -p /app/storage/framework/cache/data \
    /app/storage/framework/sessions \
    /app/storage/framework/views \
    /app/storage/logs \
    /app/bootstrap/cache \
    /app/database \
    && touch /app/database/database.sqlite \
    && chown -R www-data:www-data /app/storage /app/bootstrap/cache /app/database \
    && chmod -R 775 /app/storage /app/bootstrap/cache /app/database

# Copy Nginx config & Entrypoint
COPY docker/nginx.conf /etc/nginx/http.d/default.conf
COPY docker/entrypoint.sh /usr/local/bin/entrypoint.sh
RUN chmod +x /usr/local/bin/entrypoint.sh

EXPOSE 80

CMD ["/usr/local/bin/entrypoint.sh"]
