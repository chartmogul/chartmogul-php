FROM php:7.2-fpm-stretch
RUN curl -O https://getcomposer.org/composer.phar
RUN mv composer.phar /usr/local/bin/composer
RUN chmod a+x /usr/local/bin/composer
RUN pecl install xdebug-2.7.0alpha1 && docker-php-ext-enable xdebug
RUN apt-get update && apt-get  install -y git unzip
