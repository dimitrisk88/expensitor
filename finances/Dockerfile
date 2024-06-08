FROM php:8.3-fpm

RUN apt-get update && apt-get install -y \
    libzip-dev \
    zip \
    unzip \
    git \
    && docker-php-ext-install zip

WORKDIR /var/www/html

# Install Composer
COPY --from=composer/composer:latest-bin /composer /usr/local/bin

COPY composer.json /var/www/html/
RUN if [ -f ./composer.lock ]; then cp ./composer.lock /var/www/html/; fi

# Install dependencies
ENV COMPOSER_ALLOW_SUPERUSER=1
RUN if [ -f /var/www/html/composer.lock ]; then composer update; else composer install; fi

# Create Symfony project if not exists
RUN if [ ! -f bin/console ]; then composer create-project symfony/skeleton ./app; fi

# Install additional Symfony packages if required
RUN composer require symfony/orm-pack symfony/form symfony/security-bundle symfony/maker-bundle symfony/twig-pack symfony/validator symfony/mailer

# Install PHPUnit for testing
RUN composer require --dev phpunit/phpunit

CMD ["php-fpm"]