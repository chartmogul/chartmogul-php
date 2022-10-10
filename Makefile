RUNNER=docker run -it --rm --workdir "/src" -v "$(PWD):/src" -v "$(HOME)/.composer/cache:/root/.composer/cache" chartmogulphp74 /bin/bash -c

.PHONY: build composer php

build:
	@docker build --build-arg VERSION=7.4 --tag=chartmogulphp74 .
	@docker build --build-arg VERSION=8.0 --tag=chartmogulphp80 .
	@docker build --build-arg VERSION=8.1 --tag=chartmogulphp81 .
composer:
	$(RUNNER) "composer $(filter-out $@,$(MAKECMDGOALS))"
dependencies:
	make -s composer update -- --prefer-dist
test:
	$(RUNNER) "phpunit --coverage-text --coverage-html ./coverage"
phpunit:
	$(RUNNER) "phpunit $(filter-out $@,$(MAKECMDGOALS))"
php:
	$(RUNNER) "php $(filter-out $@,$(MAKECMDGOALS))"
%:
	@:
