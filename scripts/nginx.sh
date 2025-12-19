#!/usr/bin/env bash
# Open interactive shell in Nginx container

source "$(dirname "$0")/.include.sh"

set -e

display_help() {
    echo -e "${COLOR_GREEN}nginx.sh${COLOR_DEFAULT} - Open interactive shell in Nginx container"
    echo ""
    echo "Usage: ./scripts/nginx.sh [OPTIONS]"
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

echo -e "${COLOR_GREEN}Opening shell in Nginx container...${COLOR_DEFAULT}"
${DOCKER_COMPOSE} exec nginx sh
