RUNNER=docker run -it --rm --workdir "/src" -v "$(PWD):/src" -v "$(HOME)/.composer/cache:/root/.composer/cache" chartmogulphp82 /bin/bash -c
RUNNER_NI=docker run --rm --workdir "/src" -v "$(PWD):/src" -v "$(HOME)/.composer/cache:/root/.composer/cache" chartmogulphp82 /bin/bash -c

.PHONY: build composer php lint analyse

build:
	@docker build --build-arg VERSION=7.4 --tag=chartmogulphp74 .
	@docker build --build-arg VERSION=8.0 --tag=chartmogulphp80 .
	@docker build --build-arg VERSION=8.1 --tag=chartmogulphp81 .
	@docker build --build-arg VERSION=8.2 --tag=chartmogulphp82 .
composer:
	$(RUNNER) "composer $(filter-out $@,$(MAKECMDGOALS))"
dependencies:
	make -s composer update -- --prefer-dist
test:
	$(RUNNER) "XDEBUG_MODE=coverage phpunit --coverage-text --coverage-html ./coverage"
phpunit:
	$(RUNNER) "phpunit $(filter-out $@,$(MAKECMDGOALS))"
php:
	$(RUNNER) "php $(filter-out $@,$(MAKECMDGOALS))"
lint:
	$(RUNNER_NI) "composer update --no-interaction --quiet && vendor/bin/php-cs-fixer fix --dry-run --no-interaction src"
analyse:
	$(RUNNER_NI) "composer update --no-interaction --quiet && composer global require --quiet phpstan/phpstan && phpstan analyse"
%:
	@:
