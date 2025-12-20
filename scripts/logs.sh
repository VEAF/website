#!/usr/bin/env bash
# Display Docker container logs

source "$(dirname "$0")/.include.sh"

set -e

display_help() {
    echo -e "${COLOR_GREEN}logs.sh${COLOR_DEFAULT} - Display Docker container logs"
    echo ""
    echo "Usage: ./scripts/logs.sh [OPTIONS] [SERVICE...]"
    echo ""
    echo "Options:"
    echo "  --tail=N    Number of lines to show (default: 1000)"
    echo "  --help      Display this help message"
    echo ""
    echo "Examples:"
    echo "  ./scripts/logs.sh              # Show logs for all services"
    echo "  ./scripts/logs.sh php          # Show logs for php service"
    echo "  ./scripts/logs.sh --tail=100   # Show last 100 lines"
}

TAIL=1000
SERVICES=""

# Parse arguments
while [[ $# -gt 0 ]]; do
    case $1 in
        --tail=*)
            TAIL="${1#*=}"
            shift
            ;;
        --help|-h)
            display_help
            exit 0
            ;;
        -*)
            echo -e "${COLOR_RED}Unknown option: $1${COLOR_DEFAULT}"
            display_help
            exit 1
            ;;
        *)
            SERVICES="${SERVICES} $1"
            shift
            ;;
    esac
done

check_not_in_container

cd "${PROJECT_ROOT}"

${DOCKER_COMPOSE} logs -tf --tail=${TAIL} ${SERVICES}
