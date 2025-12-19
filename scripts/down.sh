#!/usr/bin/env bash
# Stop and remove Docker containers and volumes

source "$(dirname "$0")/.include.sh"

set -e

display_help() {
    echo -e "${COLOR_GREEN}down.sh${COLOR_DEFAULT} - Stop and remove Docker containers"
    echo ""
    echo "Usage: ./scripts/down.sh [OPTIONS]"
    echo ""
    echo "Options:"
    echo "  --keep-volumes    Do not remove volumes"
    echo "  --help            Display this help message"
}

KEEP_VOLUMES=0

# Parse arguments
while [[ $# -gt 0 ]]; do
    case $1 in
        --keep-volumes)
            KEEP_VOLUMES=1
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

echo -e "${COLOR_GREEN}Stopping and removing Docker containers...${COLOR_DEFAULT}"

if [ ${KEEP_VOLUMES} -eq 1 ]; then
    ${DOCKER_COMPOSE} down --remove-orphans
else
    ${DOCKER_COMPOSE} down --remove-orphans --volumes
fi
