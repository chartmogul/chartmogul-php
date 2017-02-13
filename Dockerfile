FROM php:5.5.37-fpm
RUN curl -O https://getcomposer.org/composer.phar
RUN pecl install xdebug && docker-php-ext-enable xdebug
RUN apt-get update && apt-get  install -y git unzip
