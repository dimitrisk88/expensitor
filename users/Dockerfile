FROM php:8.3-fpm

RUN apt-get update && apt-get install -y \
    libzip-dev \
    zip \
    unzip \
    nano \
    net-tools \
    iputils-ping \
    && docker-php-ext-install zip pdo pdo_mysql

WORKDIR /var/www/html

# Install Composer
COPY --from=composer/composer:latest-bin /composer /usr/local/bin

# Copy the application files
COPY . .

# Install dependencies
ENV COMPOSER_ALLOW_SUPERUSER=1
RUN composer install

# Ensure permissions for var directory
RUN chown -R www-data:www-data /var/www/html/var

CMD ["php-fpm"]