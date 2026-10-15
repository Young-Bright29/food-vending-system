FROM richarvey/nginx-php-fpm:latest

# Copy your Laravel project files into the container
COPY . /var/www/html

# Set the web root directory to Laravel's public folder
ENV WEBROOT /var/www/html/public
ENV APP_ENV production
ENV REPO_TYPE git

# Run composer to install dependencies without development tools
# RUN composer install --no-dev --optimize-autoloader



FROM php:8.3-cli

WORKDIR /var/www/html

RUN apt-get update && apt-get install -y \
    git \
    unzip \
    libzip-dev \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    && docker-php-ext-install pdo_mysql mbstring zip exif pcntl bcmath

COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

COPY . .

# RUN composer install --no-dev --optimize-autoloader

# RUN composer install --no-dev --optimize-autoloader --ignore-platform-reqs

RUN composer update --no-dev --optimize-autoloader --no-interaction


# Set permissions for Laravel storage and bootstrap cache
# RUN chown -R nw:nw /var/www/html/storage /var/www/html/bootstrap/cache

RUN php artisan storage:link || true

RUN php artisan make:notifications-table
RUN php artisan make:notification NewOrderForVendor

RUN chmod -R 775 storage bootstrap/cache

EXPOSE 10000

CMD php artisan serve --host=0.0.0.0 --port=${PORT:-10000}

