# Common functions and constants for all scripts
# Usage: source $(dirname $0)/.include.sh

# Colors for terminal output
COLOR_RED="\e[31m"
COLOR_GREEN="\e[32m"
COLOR_YELLOW="\e[33m"
COLOR_BLUE="\e[34m"
COLOR_DEFAULT="\e[39m"

# Docker compose command
DOCKER_COMPOSE="docker compose"

# Detect TTY for docker exec
if [ -t 0 ]; then
    # Interactive terminal
    TTY_COMPOSE=""
else
    # Non-interactive (script/CI)
    TTY_COMPOSE="-T"
fi

# PHP command via docker compose
COMPOSE_PHP_CMD="${DOCKER_COMPOSE} exec ${TTY_COMPOSE} -u www-data php"

# Get the project root directory (parent of scripts/)
PROJECT_ROOT="$(cd "$(dirname "${BASH_SOURCE[0]}")/.." && pwd)"

# Check that we are not running inside a container
check_not_in_container() {
    if [ -f /.dockerenv ]; then
        echo -e "${COLOR_RED}Error: this script cannot be run inside a container${COLOR_DEFAULT}"
        exit 1
    fi
}

# Initialize configuration files from .dist templates if they don't exist
autoconf() {
    local changed=0

    if [ ! -f "${PROJECT_ROOT}/.env" ]; then
        echo -e "${COLOR_GREEN}Creating .env from .env.dist${COLOR_DEFAULT}"
        cp "${PROJECT_ROOT}/.env.dist" "${PROJECT_ROOT}/.env"
        changed=1
    fi

    if [ ! -f "${PROJECT_ROOT}/.php.env" ]; then
        echo -e "${COLOR_GREEN}Creating .php.env from .php.env.dist${COLOR_DEFAULT}"
        cp "${PROJECT_ROOT}/.php.env.dist" "${PROJECT_ROOT}/.php.env"
        changed=1
    fi

    if [ ! -f "${PROJECT_ROOT}/docker-compose.yml" ]; then
        echo -e "${COLOR_GREEN}Creating docker-compose.yml from docker-compose.yml.dist${COLOR_DEFAULT}"
        cp "${PROJECT_ROOT}/docker-compose.yml.dist" "${PROJECT_ROOT}/docker-compose.yml"
        changed=1
    fi

    return $changed
}
