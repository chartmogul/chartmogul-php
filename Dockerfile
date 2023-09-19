ARG VERSION
FROM php:${VERSION}
RUN curl -O https://getcomposer.org/composer.phar
RUN mv composer.phar /usr/local/bin/composer
RUN chmod a+x /usr/local/bin/composer
RUN apt-get update && apt-get  install -y git unzip
ARG VERSION
RUN if [ "$VERSION" = "7.4" ]; then \
        composer global require phpunit/phpunit:^8 && \
        pecl install xdebug-2.9.8; \
    elif [ "$VERSION" = "8.0" ]; then \
        composer global require phpunit/phpunit:^9 && \
        pecl install xdebug-3.0.0; \
    elif [ "$VERSION" = "8.1" ]; then \
        composer global require phpunit/phpunit:^9 && \
        pecl install xdebug-3.1.5; \
    else \
        composer global require phpunit/phpunit:^9 && \
        pecl install xdebug; \
    fi
RUN docker-php-ext-enable xdebug
ENV PATH="${PATH}:/root/.composer/vendor/bin"
COPY php.ini /usr/local/etc/php/
