DOCKER_COMPOSE_FILE=./docker-compose.yml
DOCKER_COMPOSE_DIR=./.docker
DOCKER_COMPOSE=docker compose --env-file $(DOCKER_COMPOSE_DIR)/.env -f $(DOCKER_COMPOSE_FILE)
TOPDIR=$(shell pwd)
USER_ID=$(shell id -u)

.PHONY: setup
setup: init
	$(DOCKER_COMPOSE) run workspace composer install

workspace: init
	$(DOCKER_COMPOSE) run workspace sh


~/.cache/composer:
	mkdir ~/.cache/composer

.docker/.env:
	cp $(DOCKER_COMPOSE_DIR)/.env.example $(DOCKER_COMPOSE_DIR)/.env

.PHONY: init
init: .docker/.env ~/.cache/composer