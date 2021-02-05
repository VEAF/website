# autogenerated script from
.PHONY: help

-include custom.Makefile

# User Id
UNAME = $(shell uname)

ifeq ($(UNAME), Linux)
    UID = $(shell id -u)
else
    UID = 1000
endif

ifneq ("$(wildcard .env)","")
	include .env
	export
endif

INTERACTIVE:=$(shell [ -t 0 ] && echo 1)

ifdef INTERACTIVE
# is a terminal
	TTY_DOCKER=-it
	TTY_COMPOSE=
else
# bash job
	TTY_DOCKER=
	TTY_COMPOSE=-T
endif

COMPOSE=docker-compose

ifneq ("$(wildcard /.dockerenv)","")
	echo "Should not be used inside a container"
	exit 1
else
	COMPOSE_PHP_CMD=$(COMPOSE) exec $(TTY_COMPOSE) -u www-data php
endif

## Display this help text
help:
	$(info ---------------------------------------------------------------------)
	$(info -                        Available targets                          -)
	$(info ---------------------------------------------------------------------)
	@awk '/^[a-zA-Z\-\_0-9]+:/ {                                   \
	nb = sub( /^## /, "", helpMsg );                             \
	if(nb == 0) {                                                \
		helpMsg = $$0;                                             \
		nb = sub( /^[^:]*:.* ## /, "", helpMsg );                  \
	}                                                            \
	if (nb)                                                      \
		printf "\033[1;31m%-" width "s\033[0m %s\n", $$1, helpMsg; \
	}                                                              \
	{ helpMsg = $$0 }'                                             \
	$(MAKEFILE_LIST) | column -ts:

#==============================================================================
# Auto conf
#==============================================================================

.env:
	cp .env.dist .env

.php.env:
	cp .php.env.dist .php.env

autoconf: .env .php.env

#==============================================================================
# Standard docker dev commands
#==============================================================================

## Pull images used in docker-compose config
pull: autoconf
	docker-compose pull --no-parallel

## Start all the containers
up: autoconf
	docker-compose up -d

## Alias -> up
start: up

## Stop all the containers
stop:
	docker-compose stop

## Stop, then... start
restart: stop start

## Down all the containers
down:
	docker-compose down --remove-orphans --volumes

## Logs for all containers of the project
logs:
	docker-compose logs -tf --tail=1000

## Status of containers
ps:
	docker-compose ps

#==============================================================================
# Interactive shells
#==============================================================================

## Enter interactive shell into php container
php: hooks
	docker-compose exec --user www-data php bash

## Enter interactive shell into nginx container
nginx:
	docker-compose exec nginx sh

#==============================================================================
# Shortcuts
#==============================================================================

## Symfony cache clear
cc:
	$(COMPOSE_PHP_CMD) ./bin/console cache:clear

## CS Fixer
fix:
# 	$(COMPOSE_PHP_CMD) vendor/bin/php-cs-fixer fix src

## Run phpcsfixer
fix-hook:

# Install git hooks
hooks:
#	@ln -s -f "./../../.dev/git/pre-commit" ".git/hooks/pre-commit"

# Just wait php ready
wait:
	@$(COMPOSE) run php echo "Container : php is now ready"

## Upgrade sources + rebuild container + launch migrations
upgrade: pull up wait internal_update front_update

## composer + upgrade (migrations,...)
internal_update:
	$(COMPOSE_PHP_CMD) composer install
	$(COMPOSE_PHP_CMD) ./bin/console doctrine:migrations:migrate -n
