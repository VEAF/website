#!/usr/bin/env bash
# Start Docker containers

source "$(dirname "$0")/.include.sh"

set -e

DO_PULL=false

display_help() {
    echo -e "${COLOR_GREEN}start.sh${COLOR_DEFAULT} - Start Docker containers"
    echo ""
    echo "Usage: ./scripts/start.sh [OPTIONS]"
    echo ""
    echo "Options:"
    echo "  --pull    Pull images before starting"
    echo "  --help    Display this help message"
}

# Parse arguments
while [[ $# -gt 0 ]]; do
    case $1 in
        --pull)
            DO_PULL=true
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
autoconf

cd "${PROJECT_ROOT}"

if [[ "$DO_PULL" == "true" ]]; then
    echo -e "${COLOR_GREEN}Pulling Docker images...${COLOR_DEFAULT}"
    ${DOCKER_COMPOSE} pull
fi

echo -e "${COLOR_GREEN}Starting Docker containers...${COLOR_DEFAULT}"
${DOCKER_COMPOSE} up -d --remove-orphans
