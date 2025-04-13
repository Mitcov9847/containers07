FROM php:7.4-fpm

RUN apt-get update && apt-get install -y libmcrypt-dev libpng-dev libjpeg-dev libfreetype6-dev && \
    docker-php-ext-configure gd --with-freetype --with-jpeg && \
    docker-php-ext-install gd mysqli && \
    apt-get clean && \
    rm -rf /var/lib/apt/lists/*
