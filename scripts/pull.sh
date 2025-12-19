#!/usr/bin/env bash
# Pull Docker images

source "$(dirname "$0")/.include.sh"

set -e

display_help() {
    echo -e "${COLOR_GREEN}pull.sh${COLOR_DEFAULT} - Pull Docker images"
    echo ""
    echo "Usage: ./scripts/pull.sh [OPTIONS]"
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
autoconf

cd "${PROJECT_ROOT}"

echo -e "${COLOR_GREEN}Pulling Docker images...${COLOR_DEFAULT}"
${DOCKER_COMPOSE} pull --no-parallel
