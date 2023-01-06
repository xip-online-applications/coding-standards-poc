SHELL := /bin/bash
.PHONY: prepare prepare-gitpods build start stop status logs bash reset

DOCKER ?= docker
DOCKER_COMPOSE ?= $(DOCKER) compose
PHP ?= $(DOCKER_COMPOSE) exec php

prepare:
	cp .docker-compose.env.dist .docker-compose.env
	mkdir -p application/var application/vendor
	chmod 777 -R application/var application/vendor

build:
	$(DOCKER_COMPOSE) pull
	$(DOCKER_COMPOSE) build

start:
	$(DOCKER_COMPOSE) up -d

stop:
	$(DOCKER_COMPOSE) down

restart:
	$(MAKE) stop
	$(MAKE) start

status:
	$(DOCKER_COMPOSE) ps

logs:
	$(DOCKER_COMPOSE) logs -f

bash:
	$(PHP) bash

reset:
	$(MAKE) build
	$(DOCKER_COMPOSE) run php bin/reset

qa:
	$(PHP) composer qa

unit:
	$(PHP) composer unit

cc:
	$(PHP) bin/console cache:clear
