#!/usr/bin/env bash
# Open interactive shell in PHP container

source "$(dirname "$0")/.include.sh"

set -e

display_help() {
    echo -e "${COLOR_GREEN}php.sh${COLOR_DEFAULT} - Open interactive shell in PHP container"
    echo ""
    echo "Usage: ./scripts/php.sh [OPTIONS]"
    echo ""
    echo "Options:"
    echo "  --root    Connect as root instead of www-data"
    echo "  --help    Display this help message"
}

USER="www-data"

# Parse arguments
while [[ $# -gt 0 ]]; do
    case $1 in
        --root)
            USER="root"
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

check_not_in_container

cd "${PROJECT_ROOT}"

echo -e "${COLOR_GREEN}Opening shell in PHP container as ${USER}...${COLOR_DEFAULT}"
${DOCKER_COMPOSE} exec --user ${USER} php bash
