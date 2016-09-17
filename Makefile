IMAGE=php:5.5.37-fpm

#builder image
define Dockerfile =
FROM $(IMAGE)
RUN curl -O https://getcomposer.org/composer.phar
RUN pecl install xdebug && docker-php-ext-enable xdebug
RUN apt-get update && apt-get  install -y git unzip
endef
export Dockerfile

RUNNER=docker run -it --rm --workdir "/src" -v "$(PWD):/src" chartmogulphp /bin/bash -c

.PHONY: build composer php

build:
	if [ "$(shell docker images -q chartmogulphp 2> /dev/null)" = "" ]; then echo "$$Dockerfile" | docker build --tag=chartmogulphp -; fi;
	if [ ! -d vendor ]; then make composer install; fi;
composer: build
	$(RUNNER) "php composer.phar $(filter-out $@,$(MAKECMDGOALS))"
test: build
	$(RUNNER) "./vendor/bin/phpunit --coverage-text --coverage-html ./coverage "
php: build
	$(RUNNER) "php $(filter-out $@,$(MAKECMDGOALS))"
cs: build
	$(RUNNER) "./vendor/bin/phpcs --standard=PSR2 src/"
cbf: build
	$(RUNNER) "./vendor/bin/phpcbf --standard=PSR2 src/"
doc: build
	$(RUNNER) "./vendor/bin/phpdoc"
	$(RUNNER) "./vendor/bin/phpdocmd docs/structure.xml docs --index README.md"
%:
	@: