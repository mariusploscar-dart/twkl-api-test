FROM php:8.3-apache

RUN apt-get -y update \
    && apt-get -y install git zlib1g-dev libzip-dev unzip \
    && a2enmod rewrite \
    && docker-php-ext-install mysqli pdo pdo_mysql zip \
    && pecl install xdebug-3.3.1 \
    && docker-php-ext-enable xdebug
COPY --from=composer:latest /usr/bin/composer /usr/local/bin/composer

WORKDIR /var/www