#!/usr/bin/env bash
# Two-step release preparation script
# Step 1 (prepare): Validates environment, starts git-flow release, suggests fix commands
# Step 2 (finish): Finalizes the release and pushes to remote

source "$(dirname "$0")/../.include.sh"

set -e

# Release configuration
RELEASE_FILE="${PROJECT_ROOT}/config/packages/release.yaml"
MAIN_BRANCH="master"
DEV_BRANCH="develop"

display_help() {
    echo -e "${COLOR_GREEN}release.sh${COLOR_DEFAULT} - Two-step release preparation"
    echo ""
    echo "Usage: ./scripts/dev/release.sh <command> [OPTIONS]"
    echo ""
    echo "Commands:"
    echo "  prepare    Start a new release (step 1)"
    echo "  finish     Finalize and push the release (step 2)"
    echo ""
    echo "Options:"
    echo "  --help     Display this help message"
    echo ""
    echo "Examples:"
    echo "  ./scripts/dev/release.sh prepare"
    echo "  ./scripts/dev/release.sh finish"
}

# Get current version from release.yaml
get_current_version() {
    grep 'app_version:' "${RELEASE_FILE}" | sed 's/.*app_version:\s*//' | tr -d ' '
}

# Get current git branch
get_current_branch() {
    git rev-parse --abbrev-ref HEAD
}

# Check if branch is allowed for release
check_allowed_branch() {
    local branch="$1"

    if [[ "${branch}" == "${DEV_BRANCH}" ]] || \
       [[ "${branch}" == "${MAIN_BRANCH}" ]] || \
       [[ "${branch}" =~ ^support/ ]]; then
        return 0
    fi
    return 1
}

# Check for uncommitted changes
check_clean_working_tree() {
    if ! git diff --quiet || ! git diff --cached --quiet; then
        return 1
    fi

    # Check for untracked files that might be important
    local untracked=$(git status --porcelain | grep -c "^??" || true)
    if [[ ${untracked} -gt 0 ]]; then
        echo -e "${COLOR_YELLOW}Warning: ${untracked} untracked file(s) detected${COLOR_DEFAULT}"
        git status --porcelain | grep "^??"
        echo ""
    fi

    return 0
}

# Update local branches from remote
update_branches() {
    echo -e "${COLOR_BLUE}Updating local branches...${COLOR_DEFAULT}"

    local current_branch=$(get_current_branch)

    # Fetch all remotes
    git fetch --all --prune

    # Update develop
    if git show-ref --verify --quiet "refs/heads/${DEV_BRANCH}"; then
        echo -e "Updating ${COLOR_GREEN}${DEV_BRANCH}${COLOR_DEFAULT}..."
        git checkout "${DEV_BRANCH}" && git pull origin "${DEV_BRANCH}"
    fi

    # Update master
    if git show-ref --verify --quiet "refs/heads/${MAIN_BRANCH}"; then
        echo -e "Updating ${COLOR_GREEN}${MAIN_BRANCH}${COLOR_DEFAULT}..."
        git checkout "${MAIN_BRANCH}" && git pull origin "${MAIN_BRANCH}"
    fi

    # Return to original branch
    git checkout "${current_branch}"
}

# Suggest next version based on current
suggest_next_version() {
    local current="$1"

    # Parse semver (major.minor.patch)
    local major minor patch
    IFS='.' read -r major minor patch <<< "${current}"

    # Suggest patch increment
    local next_patch="${major}.${minor}.$((patch + 1))"
    local next_minor="${major}.$((minor + 1)).0"
    local next_major="$((major + 1)).0.0"

    echo -e "Suggested versions:"
    echo -e "  Patch: ${COLOR_GREEN}${next_patch}${COLOR_DEFAULT}"
    echo -e "  Minor: ${COLOR_YELLOW}${next_minor}${COLOR_DEFAULT}"
    echo -e "  Major: ${COLOR_RED}${next_major}${COLOR_DEFAULT}"
}

# Step 1: Prepare the release
cmd_prepare() {
    echo -e "${COLOR_GREEN}=== Release Preparation (Step 1) ===${COLOR_DEFAULT}"
    echo ""

    cd "${PROJECT_ROOT}"

    # Check current branch
    local current_branch=$(get_current_branch)
    echo -e "Current branch: ${COLOR_BLUE}${current_branch}${COLOR_DEFAULT}"

    if ! check_allowed_branch "${current_branch}"; then
        echo -e "${COLOR_RED}Error: Releases can only be created from:${COLOR_DEFAULT}"
        echo -e "  - ${DEV_BRANCH}"
        echo -e "  - ${MAIN_BRANCH}"
        echo -e "  - support/*"
        echo ""
        echo -e "Current branch '${current_branch}' is not allowed."
        exit 1
    fi

    # Check for uncommitted changes
    echo ""
    echo -e "${COLOR_BLUE}Checking working tree...${COLOR_DEFAULT}"
    if ! check_clean_working_tree; then
        echo -e "${COLOR_RED}Error: You have uncommitted changes.${COLOR_DEFAULT}"
        echo ""
        echo "Please commit or stash your changes before starting a release."
        echo ""
        git status --short
        exit 1
    fi
    echo -e "${COLOR_GREEN}Working tree is clean.${COLOR_DEFAULT}"

    # Show current version
    echo ""
    local current_version=$(get_current_version)
    echo -e "Current version: ${COLOR_YELLOW}${current_version}${COLOR_DEFAULT}"
    echo ""
    suggest_next_version "${current_version}"
    echo ""

    # Ask for new version
    read -p "Enter new version: " new_version

    if [[ -z "${new_version}" ]]; then
        echo -e "${COLOR_RED}Error: Version cannot be empty.${COLOR_DEFAULT}"
        exit 1
    fi

    # Validate version format (simple semver check)
    if ! [[ "${new_version}" =~ ^[0-9]+\.[0-9]+\.[0-9]+$ ]]; then
        echo -e "${COLOR_RED}Error: Invalid version format. Expected: X.Y.Z${COLOR_DEFAULT}"
        exit 1
    fi

    # Update branches
    echo ""
    update_branches

    # Return to source branch and start release
    echo ""
    echo -e "${COLOR_BLUE}Starting git-flow release...${COLOR_DEFAULT}"
    git checkout "${current_branch}"
    git flow release start "${new_version}" "${current_branch}"

    # Update version in release.yaml
    echo ""
    echo -e "${COLOR_BLUE}Updating version in ${RELEASE_FILE}...${COLOR_DEFAULT}"
    sed -i "s/app_version:.*/app_version: ${new_version}/" "${RELEASE_FILE}"
    echo -e "${COLOR_GREEN}Version updated to ${new_version}${COLOR_DEFAULT}"

    # Display next steps
    echo ""
    echo -e "${COLOR_GREEN}=== Release ${new_version} prepared ===${COLOR_DEFAULT}"
    echo ""
    echo -e "${COLOR_YELLOW}Next steps:${COLOR_DEFAULT}"
    echo ""
    echo "1. Merge pending changelogs:"
    echo -e "   ${COLOR_BLUE}./scripts/dev/changelog.sh ${new_version}${COLOR_DEFAULT}"
    echo ""
    echo "2. Fix coding standards:"
    echo -e "   ${COLOR_BLUE}./scripts/fix.sh${COLOR_DEFAULT}"
    echo ""
    echo "3. Review and commit changes:"
    echo -e "   ${COLOR_BLUE}git add .${COLOR_DEFAULT}"
    echo -e "   ${COLOR_BLUE}git commit -m \"${new_version}\"${COLOR_DEFAULT}"
    echo ""
    echo "4. When ready, finalize the release:"
    echo -e "   ${COLOR_BLUE}./scripts/dev/release.sh finish${COLOR_DEFAULT}"
}

# Step 2: Finish the release
cmd_finish() {
    echo -e "${COLOR_GREEN}=== Release Finalization (Step 2) ===${COLOR_DEFAULT}"
    echo ""

    cd "${PROJECT_ROOT}"

    # Check we are on a release branch
    local current_branch=$(get_current_branch)
    if ! [[ "${current_branch}" =~ ^release/ ]]; then
        echo -e "${COLOR_RED}Error: You must be on a release branch.${COLOR_DEFAULT}"
        echo -e "Current branch: ${current_branch}"
        echo ""
        echo "Did you run './scripts/dev/release.sh prepare' first?"
        exit 1
    fi

    # Extract version from branch name
    local version="${current_branch#release/}"
    echo -e "Finalizing release: ${COLOR_YELLOW}${version}${COLOR_DEFAULT}"

    # Check for uncommitted changes
    echo ""
    echo -e "${COLOR_BLUE}Checking working tree...${COLOR_DEFAULT}"
    if ! check_clean_working_tree; then
        echo -e "${COLOR_RED}Error: You have uncommitted changes.${COLOR_DEFAULT}"
        echo ""
        echo "Please commit your changes before finishing the release:"
        echo -e "   ${COLOR_BLUE}git add .${COLOR_DEFAULT}"
        echo -e "   ${COLOR_BLUE}git commit -m \"${version}\"${COLOR_DEFAULT}"
        echo ""
        git status --short
        exit 1
    fi
    echo -e "${COLOR_GREEN}Working tree is clean.${COLOR_DEFAULT}"

    # Publish release branch (if not already done)
    echo ""
    echo -e "${COLOR_BLUE}Publishing release branch...${COLOR_DEFAULT}"
    git flow release publish "${version}" || true

    # Finish the release
    echo ""
    echo -e "${COLOR_BLUE}Finishing git-flow release...${COLOR_DEFAULT}"

    # Set GIT_MERGE_AUTOEDIT to avoid editor for merge commits
    export GIT_MERGE_AUTOEDIT=no
    git flow release finish -m "${version}" "${version}"

    # Push everything
    echo ""
    echo -e "${COLOR_BLUE}Pushing to remote...${COLOR_DEFAULT}"

    echo "Pushing tag ${version}..."
    git push origin "${version}"

    echo "Pushing ${DEV_BRANCH}..."
    git push origin "${DEV_BRANCH}"

    echo "Pushing ${MAIN_BRANCH}..."
    git push origin "${MAIN_BRANCH}"

    echo ""
    echo -e "${COLOR_GREEN}=== Release ${version} completed! ===${COLOR_DEFAULT}"
    echo ""
    echo "The following have been pushed:"
    echo -e "  - Tag: ${COLOR_YELLOW}${version}${COLOR_DEFAULT}"
    echo -e "  - Branch: ${COLOR_BLUE}${DEV_BRANCH}${COLOR_DEFAULT}"
    echo -e "  - Branch: ${COLOR_BLUE}${MAIN_BRANCH}${COLOR_DEFAULT}"
}

# Main entry point
check_not_in_container

case "${1:-}" in
    prepare)
        cmd_prepare
        ;;
    finish)
        cmd_finish
        ;;
    --help|-h|"")
        display_help
        exit 0
        ;;
    *)
        echo -e "${COLOR_RED}Unknown command: $1${COLOR_DEFAULT}"
        echo ""
        display_help
        exit 1
        ;;
esac
