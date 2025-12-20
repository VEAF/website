#!/usr/bin/env bash
# Load fixtures (dev only!) - drops and recreates the database

source "$(dirname "$0")/../.include.sh"

set -e

display_help() {
    echo -e "${COLOR_GREEN}fixtures.sh${COLOR_DEFAULT} - Load test fixtures"
    echo ""
    echo "Usage: ./scripts/dev/fixtures.sh [OPTIONS]"
    echo ""
    echo -e "${COLOR_YELLOW}WARNING: This script drops and recreates the database!${COLOR_DEFAULT}"
    echo -e "${COLOR_YELLOW}Never run this in production.${COLOR_DEFAULT}"
    echo ""
    echo "Safety: Requires a '.fixtures' file in the project root."
    echo "        Create it with: touch .fixtures"
    echo ""
    echo "Options:"
    echo "  --with-migrations      Run migrations instead of schema:update"
    echo "  --without-fixtures     Skip loading fixtures (only reset DB)"
    echo "  --help                 Display this help message"
    echo ""
    echo "Examples:"
    echo "  ./scripts/dev/fixtures.sh                    # Reset DB + load fixtures"
    echo "  ./scripts/dev/fixtures.sh --with-migrations  # Reset DB + migrations + fixtures"
    echo "  ./scripts/dev/fixtures.sh --without-fixtures # Reset DB only (for migration prep)"
}

# Default values
WITH_MIGRATIONS=0
WITH_FIXTURES=1

# Parse command line
while [[ $# -gt 0 ]]; do
    case $1 in
        --with-migrations)
            WITH_MIGRATIONS=1
            shift
            ;;
        --without-fixtures)
            WITH_FIXTURES=0
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
echo -e "${COLOR_RED}Loading fixtures - never run this in PROD environment!${COLOR_DEFAULT}"

if [ "${APP_ENV}" == "prod" ]; then
    echo -e "${COLOR_RED}Error: You are in prod environment, loading fixtures is forbidden${COLOR_DEFAULT}"
    exit 2
fi

if [ ! -f "${PROJECT_ROOT}/.fixtures" ]; then
    echo -e "${COLOR_RED}Fixtures are disabled${COLOR_DEFAULT}"
    echo -e "Use ${COLOR_BLUE}touch .fixtures${COLOR_DEFAULT} to enable fixture loading"
    exit 3
fi

cd "${PROJECT_ROOT}"

${COMPOSE_PHP_CMD} ./bin/console doctrine:database:drop --force
${COMPOSE_PHP_CMD} ./bin/console doctrine:database:create

if [ ${WITH_MIGRATIONS} -ne 0 ]; then
    echo -e "${COLOR_GREEN}Running migrations...${COLOR_DEFAULT}"
    ${COMPOSE_PHP_CMD} ./bin/console doctrine:migrations:migrate -n
else
    echo -e "${COLOR_YELLOW}Running without migrations${COLOR_DEFAULT} (using schema:update)"
    echo -e "${COLOR_YELLOW}Be aware: do not generate migrations in this mode (inconsistent)${COLOR_DEFAULT}"
    ${COMPOSE_PHP_CMD} ./bin/console doctrine:schema:update --force
fi

if [ ${WITH_FIXTURES} -ne 0 ]; then
    echo -e "${COLOR_GREEN}Loading fixtures...${COLOR_DEFAULT}"
    ${COMPOSE_PHP_CMD} ./bin/console hautelook:fixtures:load -n
fi

echo -e "${COLOR_GREEN}Done!${COLOR_DEFAULT}"
