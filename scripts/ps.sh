#!/usr/bin/env bash
# Display Docker container status

source "$(dirname "$0")/.include.sh"

set -e

display_help() {
    echo -e "${COLOR_GREEN}ps.sh${COLOR_DEFAULT} - Display Docker container status"
    echo ""
    echo "Usage: ./scripts/ps.sh [OPTIONS]"
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

${DOCKER_COMPOSE} ps
