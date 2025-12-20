#!/usr/bin/env bash
# Run Symfony console command in PHP container

source "$(dirname "$0")/.include.sh"

set -e

display_help() {
    echo -e "${COLOR_GREEN}console.sh${COLOR_DEFAULT} - Run Symfony console command in PHP container"
    echo ""
    echo "Usage: ./scripts/console.sh [OPTIONS] [-- COMMAND...]"
    echo ""
    echo "Options:"
    echo "  --root    Run as root instead of www-data"
    echo "  --help    Display this help message"
    echo ""
    echo "Examples:"
    echo "  ./scripts/console.sh                      # Show Symfony console help"
    echo "  ./scripts/console.sh cache:clear          # Clear Symfony cache"
    echo "  ./scripts/console.sh -- doctrine:migrations:migrate -n"
}

USER="www-data"
CMD=()

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
            CMD=("$@")
            break
            ;;
    esac
done

check_not_in_container

cd "${PROJECT_ROOT}"

echo -e "${COLOR_GREEN}Running Symfony console as ${USER}...${COLOR_DEFAULT}"
${DOCKER_COMPOSE} exec --user ${USER} php bin/console "${CMD[@]}"
