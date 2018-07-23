RUNNER=docker run -it --rm --workdir "/src" -v "$(PWD):/src" chartmogulphp7 /bin/bash -c

.PHONY: build composer php

build:
	@if [ "$(shell docker images -q chartmogulphp7 2> /dev/null)" = "" ]; then docker build --tag=chartmogulphp7 .; fi;
composer: build
	$(RUNNER) "composer $(filter-out $@,$(MAKECMDGOALS))"
dependencies: build
	@if [ ! -d vendor ]; then make composer install; fi;
test: dependencies
	$(RUNNER) "./vendor/bin/phpunit --coverage-text --coverage-html ./coverage "
php: dependencies
	$(RUNNER) "php $(filter-out $@,$(MAKECMDGOALS))"
cs: dependencies
	$(RUNNER) "./vendor/bin/phpcs --standard=PSR2 src/"
cbf: dependencies
	$(RUNNER) "./vendor/bin/phpcbf --standard=PSR2 src/"
%:
	@:
