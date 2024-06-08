FROM php:8.3-fpm

RUN apt-get update && apt-get install -y \
    libzip-dev \
    zip \
    unzip \
    nano \
    && docker-php-ext-install zip pdo pdo_mysql

WORKDIR /var/www/html

# Install Composer
COPY --from=composer/composer:latest-bin /composer /usr/local/bin

# Copy the application files
COPY . .

# Install dependencies
ENV COMPOSER_ALLOW_SUPERUSER=1
RUN composer install

# Install gRPC PHP extension
RUN pecl install grpc && \
    docker-php-ext-enable grpc

# Expose the gRPC port (replace 50051 with your desired port)
EXPOSE 50051

# Start the PHP-FPM server
CMD ["php-fpm"]