#!/usr/bin/env bash
# Check versions of running services

source "$(dirname "$0")/.include.sh"

set -e

display_help() {
    echo -e "${COLOR_GREEN}check.sh${COLOR_DEFAULT} - Check versions of running services"
    echo ""
    echo "Usage: ./scripts/check.sh [OPTIONS]"
    echo ""
    echo "Displays version information for:"
    echo "  - Nginx version and OS"
    echo "  - PHP version and OS"
    echo ""
    echo "Options:"
    echo "  --help    Display this help message"
}

# Parse arguments
while [[ $# -gt 0 ]]; do
    case $1 in
        --help|-h)
            display_help
            exit 0
            ;;
        *)
            echo -e "${COLOR_RED}Unknown option: $1${COLOR_DEFAULT}"
            display_help
            exit 1
            ;;
    esac
done

check_not_in_container

cd "${PROJECT_ROOT}"

NGINX_VERSION="$(${DOCKER_COMPOSE} exec nginx nginx -v 2>&1)"
NGINX_OS_VERSION="Debian $(${DOCKER_COMPOSE} exec nginx cat /etc/debian_version)"

PHP_VERSION="$(${DOCKER_COMPOSE} exec php php --version | head -n 1)"
PHP_OS_VERSION="Debian $(${DOCKER_COMPOSE} exec php cat /etc/debian_version)"

echo -e "Nginx    : ${COLOR_BLUE}${NGINX_VERSION}${COLOR_DEFAULT}"
echo -e "Nginx OS : ${COLOR_BLUE}${NGINX_OS_VERSION}${COLOR_DEFAULT}"
echo -e "PHP      : ${COLOR_BLUE}${PHP_VERSION}${COLOR_DEFAULT}"
echo -e "PHP OS   : ${COLOR_BLUE}${PHP_OS_VERSION}${COLOR_DEFAULT}"
