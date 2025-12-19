#!/usr/bin/env bash
# Upgrade project: pull images, start containers, install dependencies, run migrations

source "$(dirname "$0")/.include.sh"

set -e

display_help() {
    echo -e "${COLOR_GREEN}upgrade.sh${COLOR_DEFAULT} - Upgrade project"
    echo ""
    echo "Usage: ./scripts/upgrade.sh [OPTIONS]"
    echo ""
    echo "This script performs a full project upgrade:"
    echo "  1. Initialize configuration files (autoconf)"
    echo "  2. Pull Docker images"
    echo "  3. Start Docker containers"
    echo "  4. Pull git sources (optional)"
    echo "  5. Install composer dependencies"
    echo "  6. Run database migrations"
    echo ""
    echo "Options:"
    echo "  --no-docker-pull    Skip Docker image pull"
    echo "  --no-docker-up      Skip Docker container start"
    echo "  --no-git-pull       Skip git pull on current branch"
    echo "  --no-composer       Skip composer install"
    echo "  --no-dev            Skip composer dev dependencies (or auto in prod)"
    echo "  --no-scripts        Skip composer warmup scripts"
    echo "  --no-migrations     Skip database migrations"
    echo "  --help              Display this help message"
}

# Special variable to count elapsed time
SECONDS=0

# Default values
WITH_DOCKER_PULL=1
WITH_DOCKER_UP=1
WITH_GIT_PULL=1
WITH_COMPOSER=1
WITH_COMPOSER_DEV=1
WITH_COMPOSER_SCRIPTS=1
WITH_MIGRATIONS=1

# Parse command line
while [[ $# -gt 0 ]]; do
    case $1 in
        --no-migrations)
            WITH_MIGRATIONS=0
            shift
            ;;
        --no-docker-pull)
            WITH_DOCKER_PULL=0
            shift
            ;;
        --no-docker-up)
            WITH_DOCKER_UP=0
            shift
            ;;
        --no-git-pull)
            WITH_GIT_PULL=0
            shift
            ;;
        --no-composer)
            WITH_COMPOSER=0
            shift
            ;;
        --no-dev)
            WITH_COMPOSER_DEV=0
            shift
            ;;
        --no-scripts)
            WITH_COMPOSER_SCRIPTS=0
            shift
            ;;
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

# Script entry
echo -e "${COLOR_GREEN}Upgrade is starting${COLOR_DEFAULT}"

check_not_in_container

cd "${PROJECT_ROOT}"

# Initialize configuration files
autoconf

if [ ${WITH_DOCKER_PULL} -ne 0 ]; then
    echo -e "${COLOR_GREEN}Pulling Docker images...${COLOR_DEFAULT}"
    ${DOCKER_COMPOSE} pull --no-parallel
fi

if [ ${WITH_DOCKER_UP} -ne 0 ]; then
    echo -e "${COLOR_GREEN}Starting Docker containers...${COLOR_DEFAULT}"
    ${DOCKER_COMPOSE} up -d --remove-orphans
fi

if [ ${WITH_GIT_PULL} -ne 0 ]; then
    echo -e "${COLOR_GREEN}Pulling sources from current branch...${COLOR_DEFAULT}"
    git pull
fi

if [ ${WITH_COMPOSER} -ne 0 ]; then
    COMPOSER_ARGS=""
    if [ "${APP_ENV}" == 'prod' ]; then
        COMPOSER_ARGS="${COMPOSER_ARGS} --no-dev"
    else
        if [ ${WITH_COMPOSER_DEV} -eq 0 ]; then
            COMPOSER_ARGS="${COMPOSER_ARGS} --no-dev"
        fi
    fi
    if [ ${WITH_COMPOSER_SCRIPTS} -eq 0 ]; then
        COMPOSER_ARGS="${COMPOSER_ARGS} --no-scripts"
    fi
    echo -e "${COLOR_GREEN}Installing composer dependencies...${COLOR_DEFAULT}"
    ${COMPOSE_PHP_CMD} composer install ${COMPOSER_ARGS}
fi

if [ ${WITH_MIGRATIONS} -ne 0 ]; then
    echo -e "${COLOR_GREEN}Running migrations...${COLOR_DEFAULT}"
    ${COMPOSE_PHP_CMD} ./bin/console doctrine:migrations:migrate -n
fi

echo -e "${COLOR_GREEN}Upgrade done in ${COLOR_BLUE}${SECONDS}${COLOR_GREEN} seconds${COLOR_DEFAULT}"
