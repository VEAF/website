#!/usr/bin/env bash
# Open interactive shell in PHP container or run a command

source "$(dirname "$0")/.include.sh"

set -e

display_help() {
    echo -e "${COLOR_GREEN}php.sh${COLOR_DEFAULT} - Open interactive shell in PHP container or run a command"
    echo ""
    echo "Usage: ./scripts/php.sh [OPTIONS] [-- COMMAND...]"
    echo ""
    echo "Options:"
    echo "  --root    Connect as root instead of www-data"
    echo "  --help    Display this help message"
    echo ""
    echo "Examples:"
    echo "  ./scripts/php.sh                      # Open interactive bash shell"
    echo "  ./scripts/php.sh -- php -v            # Run 'php -v' in container"
    echo "  ./scripts/php.sh --root -- composer install  # Run as root"
}

USER="www-data"
CMD=()

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
        --)
            shift
            CMD=("$@")
            break
            ;;
        *)
            # Assume everything from here is the command
            CMD=("$@")
            break
            ;;
    esac
done

check_not_in_container

cd "${PROJECT_ROOT}"

if [[ ${#CMD[@]} -eq 0 ]]; then
    echo -e "${COLOR_GREEN}Opening shell in PHP container as ${USER}...${COLOR_DEFAULT}"
    ${DOCKER_COMPOSE} exec --user ${USER} php bash
else
    echo -e "${COLOR_GREEN}Running command in PHP container as ${USER}...${COLOR_DEFAULT}"
    ${DOCKER_COMPOSE} exec --user ${USER} php "${CMD[@]}"
fi
