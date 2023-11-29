DOCKER_COMPOSE_FILE=./docker-compose.yml
DOCKER_COMPOSE_DIR=./.docker
DOCKER_COMPOSE=docker compose --env-file $(DOCKER_COMPOSE_DIR)/.env -f $(DOCKER_COMPOSE_FILE)
TOPDIR=$(shell pwd)
USER_ID=$(shell id -u)

init: .docker/.env ~/.cache/composer

setup: init
	$(DOCKER_COMPOSE) run workspace composer install

workspace: init
	$(DOCKER_COMPOSE) run --interactive workspace bash

analyse:
	$(DOCKER_COMPOSE) run workspace psalm

test:
	$(DOCKER_COMPOSE) run workspace phpunit

docs:
	docker run --rm --volume $(TOPDIR):/data phpdoc/phpdoc:3 project:run

~/.cache/composer:
	mkdir ~/.cache/composer

.docker/.env:
	cp $(DOCKER_COMPOSE_DIR)/.env.example $(DOCKER_COMPOSE_DIR)/.env
