SHELL := /bin/bash
.PHONY: prepare prepare-gitpods build start stop status logs bash reset

DOCKER ?= docker
DOCKER_COMPOSE ?= $(DOCKER) compose
PHP ?= $(DOCKER_COMPOSE) exec php

prepare:
	cp .docker-compose.env.dist .docker-compose.env
	touch application/var
	chmod 777 -R application/var

build:
	$(DOCKER_COMPOSE) pull
	$(DOCKER_COMPOSE) build

start:
	$(DOCKER_COMPOSE) up -d

stop:
	$(DOCKER_COMPOSE) down

restart:
	make stop
	make start

status:
	$(DOCKER_COMPOSE) ps

logs:
	$(DOCKER_COMPOSE) logs -f

bash:
	$(PHP) bash

reset:
	make build
	$(DOCKER_COMPOSE) run php bin/reset

qa:
	$(PHP) composer qa

unit:
	$(PHP) vendor/bin/phpunit -c phpunit.xml.dist --coverage-clover=coverage.xml --colors=always

cc:
	$(PHP) bin/console cache:clear
