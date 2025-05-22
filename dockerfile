FROM php:8.2-fpm

# 1. Cài dependencies
RUN apt-get update && apt-get install -y \
    git curl zip unzip libpq-dev \
    && docker-php-ext-install pdo pdo_pgsql

# 2. Cài Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# 3. Set working dir
WORKDIR /var/www

# 4. Copy code
COPY . .

# 5. Cài PHP packages
RUN composer install --no-dev --optimize-autoloader

# 6. Expose port cho Laravel
EXPOSE 8000

# 7. Khi container khởi động, chạy migrate rồi serve
CMD php artisan migrate --force \
    && php artisan migrate --force --path=/database/migrations/2025_05_23_000000_create_sessions_table.php \
    && php artisan config:cache \
    && php artisan route:cache \
    && php artisan serve --host=0.0.0.0 --port=8000
