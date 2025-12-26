# Release

## Using the release script (recommended)

The release process is handled by a two-step script:

```shell
# Step 1: Prepare the release
./scripts/dev/release.sh prepare
```

This command will:
- Verify you are on an allowed branch (`develop`, `master`, or `support/*`)
- Check for uncommitted changes
- Display current version and suggest next versions
- Ask for the new version number
- Update local branches from remote
- Start the git-flow release
- Update the version in `config/packages/release.yaml`
- Display commands to run for fixes and changelog

After running `prepare`, follow the suggested steps:
1. `./scripts/dev/changelog.sh <version>` - Merge pending changelogs
2. `./scripts/fix.sh` - Fix coding standards
3. `git add . && git commit -m "<version>"` - Commit changes

```shell
# Step 2: Finalize the release
./scripts/dev/release.sh finish
```

This command will:
- Verify you are on a release branch
- Check for uncommitted changes
- Publish and finish the git-flow release
- Push the tag and branches to remote

## Manual process

If you prefer to run the commands manually:

```shell
# read version, ex: 1.0.0
read VERSION
# read branch, ex: develop for standard release, master for hotfix
read BRANCH

# update all branches
git checkout develop && git pull
git checkout master && git pull

# switch on source release branch
git checkout ${BRANCH}

# start the release
git flow release start ${VERSION} ${BRANCH}

# fix version
sed -i "/  app_version:/c\  app_version: ${VERSION}" config/packages/release.yaml

# merge waiting CHANGELOGS
./scripts/dev/changelog.sh ${VERSION}

# fix coding standard
./scripts/fix.sh

# fix some remaining things

# then
git add .
git commit -m ${VERSION}
git flow release publish
git flow release finish

git push origin ${VERSION}
git push origin develop
git push origin master
```
