FROM php:7.4-fpm

RUN apt-get update && apt-get install -y \
    libpng-dev \
    libjpeg-dev \
    libfreetype6-dev \
    libzip-dev \
    zip unzip git curl \
    && docker-php-ext-install pdo_mysql gd zip

COPY --from=composer:2 /usr/bin/composer /usr/bin/composer