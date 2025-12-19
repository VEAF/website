#!/usr/bin/env bash
# Stop Docker containers

source "$(dirname "$0")/.include.sh"

set -e

display_help() {
    echo -e "${COLOR_GREEN}stop.sh${COLOR_DEFAULT} - Stop Docker containers"
    echo ""
    echo "Usage: ./scripts/stop.sh [OPTIONS]"
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

echo -e "${COLOR_GREEN}Stopping Docker containers...${COLOR_DEFAULT}"
${DOCKER_COMPOSE} stop
