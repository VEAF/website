#!/usr/bin/env bash
# Clear Symfony cache

source "$(dirname "$0")/.include.sh"

set -e

display_help() {
    echo -e "${COLOR_GREEN}cc.sh${COLOR_DEFAULT} - Clear Symfony cache"
    echo ""
    echo "Usage: ./scripts/cc.sh [OPTIONS]"
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

echo -e "${COLOR_GREEN}Clearing Symfony cache...${COLOR_DEFAULT}"
${COMPOSE_PHP_CMD} ./bin/console cache:clear
