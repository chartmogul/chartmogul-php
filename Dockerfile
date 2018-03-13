FROM php:5.5.37-fpm
RUN curl -O https://getcomposer.org/composer.phar
RUN mv composer.phar /usr/local/bin/composer
RUN chmod a+x /usr/local/bin/composer
RUN pecl install xdebug-2.5.5 && docker-php-ext-enable xdebug
RUN apt-get update && apt-get  install -y git unzip
