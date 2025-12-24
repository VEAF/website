#!/usr/bin/env bash
# Fetch the DCSServerBot OpenAPI contract and generate PHP client

source "$(dirname "$0")/../.include.sh"

set -e

# Configuration
OPENAPI_URL="http://dcs.veaf.org:9876/openapi.json"
CONTRACTS_DIR="${PROJECT_ROOT}/contracts/dcsserverbot"
OUTPUT_FILE="${CONTRACTS_DIR}/openapi.json"
GENERATOR_IMAGE="openapitools/openapi-generator-cli:latest"
CLIENT_OUTPUT_DIR="${PROJECT_ROOT}/lib/DcsServerBot"

# Default behavior: fetch and generate
DO_FETCH=true
DO_GENERATE=true

display_help() {
    echo -e "${COLOR_GREEN}fetch-dcsserverbot-openapi.sh${COLOR_DEFAULT} - Fetch DCSServerBot OpenAPI contract and generate PHP client"
    echo ""
    echo "Usage: ./scripts/dev/fetch-dcsserverbot-openapi.sh [OPTIONS]"
    echo ""
    echo "Downloads the OpenAPI specification from DCSServerBot API and generates"
    echo "a PHP client library using openapi-generator (via Docker)."
    echo ""
    echo "Options:"
    echo "  --url=<url>     Override the default API URL"
    echo "  --no-fetch      Skip fetching the OpenAPI contract (use existing)"
    echo "  --no-generate   Skip generating the PHP client"
    echo "  --help          Display this help message"
    echo ""
    echo "Output:"
    echo "  Contract: ${OUTPUT_FILE}"
    echo "  PHP client: ${CLIENT_OUTPUT_DIR}"
}

# Parse command line
while [[ $# -gt 0 ]]; do
    case $1 in
        --url=*)
            OPENAPI_URL="${1#*=}"
            shift
            ;;
        --no-fetch)
            DO_FETCH=false
            shift
            ;;
        --no-generate)
            DO_GENERATE=false
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

# Check for required tools
if [ "$DO_FETCH" = true ]; then
    if ! command -v curl &> /dev/null; then
        echo -e "${COLOR_RED}Error: curl is required but not installed${COLOR_DEFAULT}"
        exit 1
    fi

    if ! command -v jq &> /dev/null; then
        echo -e "${COLOR_RED}Error: jq is required but not installed${COLOR_DEFAULT}"
        echo -e "Install with: ${COLOR_BLUE}sudo apt install jq${COLOR_DEFAULT}"
        exit 1
    fi
fi

if [ "$DO_GENERATE" = true ]; then
    if ! command -v docker &> /dev/null; then
        echo -e "${COLOR_RED}Error: docker is required for code generation${COLOR_DEFAULT}"
        exit 1
    fi
fi

# Fetch OpenAPI contract
fetch_contract() {
    mkdir -p "${CONTRACTS_DIR}"

    echo -e "${COLOR_BLUE}Fetching OpenAPI contract from ${OPENAPI_URL}...${COLOR_DEFAULT}"

    if curl -sSf "${OPENAPI_URL}" | jq '.' > "${OUTPUT_FILE}"; then
        echo -e "${COLOR_GREEN}OpenAPI contract saved to ${OUTPUT_FILE}${COLOR_DEFAULT}"
        echo ""
        echo -e "API info:"
        jq -r '"  Title: \(.info.title // "N/A")\n  Version: \(.info.version // "N/A")\n  Description: \(.info.description // "N/A")"' "${OUTPUT_FILE}"
    else
        echo -e "${COLOR_RED}Error: Failed to fetch OpenAPI contract${COLOR_DEFAULT}"
        rm -f "${OUTPUT_FILE}"
        exit 1
    fi
}

# Generate PHP client using openapi-generator
generate_php_client() {
    if [ ! -f "${OUTPUT_FILE}" ]; then
        echo -e "${COLOR_RED}Error: OpenAPI contract not found at ${OUTPUT_FILE}${COLOR_DEFAULT}"
        echo -e "Run without --no-fetch to download the contract first."
        exit 1
    fi

    echo ""
    echo -e "${COLOR_BLUE}Generating PHP client in ${CLIENT_OUTPUT_DIR}...${COLOR_DEFAULT}"

    mkdir -p "${CLIENT_OUTPUT_DIR}"

    docker run --rm \
        -v "${PROJECT_ROOT}:/local" \
        "${GENERATOR_IMAGE}" generate \
        -i /local/contracts/dcsserverbot/openapi.json \
        -g php \
        -o /local/lib/DcsServerBot \
        --additional-properties=invokerPackage=DcsServerBot,srcBasePath=lib

    echo -e "${COLOR_GREEN}PHP client generated in ${CLIENT_OUTPUT_DIR}${COLOR_DEFAULT}"
    echo ""
    echo -e "Don't forget to run: ${COLOR_BLUE}composer dump-autoload${COLOR_DEFAULT}"
}

# Execute based on options
if [ "$DO_FETCH" = true ]; then
    fetch_contract
fi

if [ "$DO_GENERATE" = true ]; then
    generate_php_client
fi
