FROM php:8.2-cli

WORKDIR /var/www

RUN apt-get update && apt-get install -y \
    unzip zip curl git libzip-dev \
    && docker-php-ext-install pdo pdo_mysql zip

COPY . .

# 🔥 FIX INI PENTING (buat folder sebelum composer)
RUN mkdir -p bootstrap/cache storage storage/logs storage/framework/cache storage/framework/sessions storage/framework/views

RUN chmod -R 775 bootstrap storage

COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

RUN composer install --no-dev --optimize-autoloader

EXPOSE 10000

CMD php artisan serve --host=0.0.0.0 --port=10000