RUNNER=docker run -it --rm --workdir "/src" -v "$(PWD):/src" -v "$(HOME)/.composer/cache:/root/.composer/cache" chartmogulphp5 /bin/bash -c

.PHONY: build composer php

build:
	@docker build --build-arg VERSION=5.5 --tag=chartmogulphp5 .
	@docker build --build-arg VERSION=7.2 --tag=chartmogulphp7 .
composer:
	@$(RUNNER) "composer $(filter-out $@,$(MAKECMDGOALS))"
dependencies:
	make -s composer update -- --prefer-dist
test:
	$(RUNNER) "phpunit --coverage-text --coverage-html ./coverage "
php:
	$(RUNNER) "php $(filter-out $@,$(MAKECMDGOALS))"
cs:
	$(RUNNER) "./vendor/bin/phpcs --standard=PSR2 src/"
cbf:
	$(RUNNER) "./vendor/bin/phpcbf --standard=PSR2 src/"
doc:
	$(RUNNER) "./vendor/bin/phpdoc"
	$(RUNNER) "./vendor/bin/phpdocmd docs/structure.xml docs --index README.md"
%:
	@:
