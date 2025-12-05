FROM php:8.1-fpm

# Install PHP extensions
RUN docker-php-ext-install mysqli pdo pdo_mysql

# Copy PHP configuration
COPY php/php.ini /usr/local/etc/php/php.ini

WORKDIR /var/www/html

