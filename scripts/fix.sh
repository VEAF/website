#!/usr/bin/env bash
# Run PHP CS Fixer on src/

source "$(dirname "$0")/.include.sh"

set -e

display_help() {
    echo -e "${COLOR_GREEN}fix.sh${COLOR_DEFAULT} - Run PHP CS Fixer on src/"
    echo ""
    echo "Usage: ./scripts/fix.sh [OPTIONS]"
    echo ""
    echo "Options:"
    echo "  --dry-run    Show changes without applying them"
    echo "  --help       Display this help message"
}

DRY_RUN=""

# Parse arguments
while [[ $# -gt 0 ]]; do
    case $1 in
        --dry-run)
            DRY_RUN="--dry-run"
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

echo -e "${COLOR_GREEN}Running PHP CS Fixer on src/...${COLOR_DEFAULT}"
${COMPOSE_PHP_CMD} vendor/bin/php-cs-fixer fix src ${DRY_RUN}
