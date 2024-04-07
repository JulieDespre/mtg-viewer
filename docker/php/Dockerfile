FROM php:8.2-fpm

RUN apt update
RUN apt install git zip unzip libxml2-dev -y

RUN docker-php-ext-install pdo_mysql opcache

#RUN pecl install apcu xdebug
#RUN docker-php-ext-enable apcu xdebug

COPY --from=composer:latest /usr/bin/composer /usr/bin/composer
ENV COMPOSER_ALLOW_SUPERUSER=1
