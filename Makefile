RUNNER=docker run -it --rm --workdir "/src" -v "$(PWD):/src" -v "$(HOME)/.composer/cache:/root/.composer/cache" chartmogulphp71 /bin/bash -c

.PHONY: build composer php

build:
	@docker build --build-arg VERSION=7.1 --tag=chartmogulphp71 .
	@docker build --build-arg VERSION=7.2 --tag=chartmogulphp72 .
	@docker build --build-arg VERSION=7.3 --tag=chartmogulphp73 .
composer:
	@$(RUNNER) "composer $(filter-out $@,$(MAKECMDGOALS))"
dependencies:
	make -s composer update -- --prefer-dist
test:
	$(RUNNER) "phpunit --coverage-text --coverage-clover build/logs/clover.xml"
php:
	$(RUNNER) "php $(filter-out $@,$(MAKECMDGOALS))"
cs:
	$(RUNNER) "./vendor/bin/phpcs --standard=PSR2 src/"
cbf:
	$(RUNNER) "./vendor/bin/phpcbf --standard=PSR2 src/"
%:
	@:
