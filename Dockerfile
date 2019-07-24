ARG VERSION
FROM php:${VERSION}
RUN curl -O https://getcomposer.org/composer.phar
RUN mv composer.phar /usr/local/bin/composer
RUN chmod a+x /usr/local/bin/composer
RUN apt-get update && apt-get  install -y git unzip
ARG VERSION
RUN composer global require hirak/prestissimo
RUN composer global require phpunit/phpunit:^7
RUN pecl install xdebug-2.7.2;
RUN docker-php-ext-enable xdebug
ENV PATH="${PATH}:/root/.composer/vendor/bin"
COPY php.ini /usr/local/etc/php/
