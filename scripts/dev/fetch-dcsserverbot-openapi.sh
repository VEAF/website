#!/usr/bin/env bash
# Fetch the DCSServerBot OpenAPI contract for development

source "$(dirname "$0")/../.include.sh"

set -e

OPENAPI_URL="http://dcs.veaf.org:9876/openapi.json"
CONTRACTS_DIR="${PROJECT_ROOT}/contracts/dcsserverbot"
OUTPUT_FILE="${CONTRACTS_DIR}/openapi.json"

display_help() {
    echo -e "${COLOR_GREEN}fetch-dcsserverbot-openapi.sh${COLOR_DEFAULT} - Fetch DCSServerBot OpenAPI contract"
    echo ""
    echo "Usage: ./scripts/dev/fetch-dcsserverbot-openapi.sh [OPTIONS]"
    echo ""
    echo "Downloads the OpenAPI specification from DCSServerBot API and saves it"
    echo "formatted with jq for development use."
    echo ""
    echo "Options:"
    echo "  --url=<url>   Override the default API URL"
    echo "  --help        Display this help message"
    echo ""
    echo "Output: ${OUTPUT_FILE}"
}

# Parse command line
while [[ $# -gt 0 ]]; do
    case $1 in
        --url=*)
            OPENAPI_URL="${1#*=}"
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
if ! command -v curl &> /dev/null; then
    echo -e "${COLOR_RED}Error: curl is required but not installed${COLOR_DEFAULT}"
    exit 1
fi

if ! command -v jq &> /dev/null; then
    echo -e "${COLOR_RED}Error: jq is required but not installed${COLOR_DEFAULT}"
    echo -e "Install with: ${COLOR_BLUE}sudo apt install jq${COLOR_DEFAULT}"
    exit 1
fi

# Create contracts directory if needed
mkdir -p "${CONTRACTS_DIR}"

echo -e "${COLOR_BLUE}Fetching OpenAPI contract from ${OPENAPI_URL}...${COLOR_DEFAULT}"

# Fetch and format the OpenAPI contract
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
