ARG VERSION
FROM php:${VERSION}
RUN curl -O https://getcomposer.org/composer.phar
RUN mv composer.phar /usr/local/bin/composer
RUN chmod a+x /usr/local/bin/composer
RUN apt-get update && apt-get  install -y git unzip
ARG VERSION
RUN if [ "$VERSION" = "7.4" ]; then \
    composer global require phpunit/phpunit:^8; else \
    composer global require phpunit/phpunit:^9; fi
RUN pecl install xdebug-3.1.5
RUN docker-php-ext-enable xdebug
ENV PATH="${PATH}:/root/.composer/vendor/bin"
COPY php.ini /usr/local/etc/php/
