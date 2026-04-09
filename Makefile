RUNNER=docker run -it --rm --workdir "/src" -v "$(PWD):/src" -v "$(HOME)/.composer/cache:/root/.composer/cache" chartmogulphp82 /bin/bash -c

# Pinned by digest for supply chain security. To update, run:
#   curl -s "https://hub.docker.com/v2/repositories/library/php/tags/<VERSION>" | python3 -c "import sys,json; print(json.load(sys.stdin)['digest'])"
PHP74_DIGEST=sha256:620a6b9f4d4feef2210026172570465e9d0c1de79766418d3affd09190a7fda5
PHP80_DIGEST=sha256:0569e384b9064c04dec55dc6e41be41b494a878dfbb6577a7d76bd50cfd5bc00
PHP81_DIGEST=sha256:76e563191d1ade120313a8736df24154d21da5155c0756f147c0b01bd19d9087
PHP82_DIGEST=sha256:d4529ca36f2f3c64320c812cd606da2133682065f5c932b054a0755818e7ea01

.PHONY: build composer php

build:
	@docker build --build-arg IMAGE=php@$(PHP74_DIGEST) --build-arg VERSION=7.4 --tag=chartmogulphp74 .
	@docker build --build-arg IMAGE=php@$(PHP80_DIGEST) --build-arg VERSION=8.0 --tag=chartmogulphp80 .
	@docker build --build-arg IMAGE=php@$(PHP81_DIGEST) --build-arg VERSION=8.1 --tag=chartmogulphp81 .
	@docker build --build-arg IMAGE=php@$(PHP82_DIGEST) --build-arg VERSION=8.2 --tag=chartmogulphp82 .
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
%:
	@:
