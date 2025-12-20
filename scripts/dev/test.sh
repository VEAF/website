#!/usr/bin/env bash
# Run PHPUnit tests safely - never in production!

source "$(dirname "$0")/../.include.sh"

set -e

display_help() {
    echo -e "${COLOR_GREEN}test.sh${COLOR_DEFAULT} - Run PHPUnit tests"
    echo ""
    echo "Usage: ./scripts/dev/test.sh [OPTIONS] [PHPUNIT_ARGS]"
    echo ""
    echo -e "${COLOR_YELLOW}WARNING: This script runs tests which may modify the test database!${COLOR_DEFAULT}"
    echo -e "${COLOR_YELLOW}Never run this in production.${COLOR_DEFAULT}"
    echo ""
    echo "Safety: Requires a '.tests' file in the project root."
    echo "        Create it with: touch .tests"
    echo ""
    echo "Options:"
    echo "  --unit              Run only unit tests"
    echo "  --integration       Run only integration tests"
    echo "  --coverage          Generate HTML coverage report in var/coverage/"
    echo "  --coverage-text     Show coverage summary in terminal"
    echo "  --coverage-clover   Generate Clover XML report (for CI tools)"
    echo "  --filter=<pattern>  Filter tests by pattern"
    echo "  --help              Display this help message"
    echo ""
    echo "Examples:"
    echo "  ./scripts/dev/test.sh                        # Run all tests"
    echo "  ./scripts/dev/test.sh --unit                 # Run only unit tests"
    echo "  ./scripts/dev/test.sh --integration          # Run only integration tests"
    echo "  ./scripts/dev/test.sh --filter=Projection    # Run tests matching 'Projection'"
    echo "  ./scripts/dev/test.sh --coverage             # Run with HTML coverage report"
    echo "  ./scripts/dev/test.sh --coverage-text        # Run with terminal coverage summary"
    echo ""
    echo "Coverage requires Xdebug or PCOV extension in the PHP container."
}

# Default values
TESTSUITE=""
COVERAGE=""
EXTRA_ARGS=""

# Parse command line
while [[ $# -gt 0 ]]; do
    case $1 in
        --unit)
            TESTSUITE="--testsuite=Unit"
            shift
            ;;
        --integration)
            TESTSUITE="--testsuite=Integration"
            shift
            ;;
        --coverage)
            COVERAGE="--coverage-html var/coverage"
            shift
            ;;
        --coverage-text)
            COVERAGE="--coverage-text"
            shift
            ;;
        --coverage-clover)
            COVERAGE="--coverage-clover var/coverage/clover.xml"
            shift
            ;;
        --help|-h)
            display_help
            exit 0
            ;;
        *)
            EXTRA_ARGS="${EXTRA_ARGS} $1"
            shift
            ;;
    esac
done

# =============================================================================
# SECURITY CHECKS
# =============================================================================

echo -e "${COLOR_YELLOW}Running tests - never run this in PROD environment!${COLOR_DEFAULT}"

# Check APP_ENV from .env file or environment
if [ -f "${PROJECT_ROOT}/.env" ]; then
    source "${PROJECT_ROOT}/.env"
fi

if [ "${APP_ENV}" == "prod" ]; then
    echo -e "${COLOR_RED}Error: You are in prod environment, running tests is forbidden${COLOR_DEFAULT}"
    exit 2
fi

# Check for .tests file - required for ALL tests
if [ ! -f "${PROJECT_ROOT}/.tests" ]; then
    echo -e "${COLOR_RED}Error: .tests file required to run tests${COLOR_DEFAULT}"
    echo -e "Use ${COLOR_BLUE}touch .tests${COLOR_DEFAULT} to enable tests"
    echo -e "${COLOR_YELLOW}This is a safety measure to prevent accidental execution in production.${COLOR_DEFAULT}"
    exit 3
fi

# =============================================================================
# RUN TESTS
# =============================================================================

cd "${PROJECT_ROOT}"

# Create coverage directory if needed
if [[ "${COVERAGE}" == *"var/coverage"* ]]; then
    mkdir -p var/coverage
fi

echo -e "${COLOR_GREEN}Running PHPUnit tests...${COLOR_DEFAULT}"

# Build the command
CMD="./bin/phpunit"
if [ -n "${TESTSUITE}" ]; then
    CMD="${CMD} ${TESTSUITE}"
fi
if [ -n "${COVERAGE}" ]; then
    CMD="${CMD} ${COVERAGE}"
fi
if [ -n "${EXTRA_ARGS}" ]; then
    CMD="${CMD} ${EXTRA_ARGS}"
fi

# Run via Docker
${COMPOSE_PHP_CMD} ${CMD}

echo -e "${COLOR_GREEN}Tests completed!${COLOR_DEFAULT}"

# Show coverage location if HTML report was generated
if [[ "${COVERAGE}" == *"--coverage-html"* ]]; then
    echo ""
    echo -e "${COLOR_BLUE}Coverage report available at: var/coverage/index.html${COLOR_DEFAULT}"
fi
