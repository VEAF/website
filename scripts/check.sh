#!/bin/bash

# check version of running services

# common bash functions and constants
COLOR_DEFAULT='\e[39m'
COLOR_RED='\e[31m'
COLOR_GREEN='\e[32m'
COLOR_YELLOW='\e[33m'
COLOR_BLUE='\e[34m'

NGINX_VERSION="$(docker-compose exec nginx nginx -v)"
NGINX_OS_VERSION="Debian $(docker-compose exec nginx cat /etc/debian_version)"

PHP_VERSION="$(docker-compose exec php php --version | head -n 1)"
PHP_OS_VERSION="Debian $(docker-compose exec php cat /etc/debian_version)"

echo -e "Nginx    : ${COLOR_BLUE}${NGINX_VERSION}${COLOR_DEFAULT}"
echo -e "Nginx OS : ${COLOR_BLUE}${NGINX_OS_VERSION}${COLOR_DEFAULT}"
echo -e "Php      : ${COLOR_BLUE}${PHP_VERSION}${COLOR_DEFAULT}"
echo -e "Php OS   : ${COLOR_BLUE}${PHP_OS_VERSION}${COLOR_DEFAULT}"
