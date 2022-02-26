# Release

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
make fix

# fix some remaining things

# then
git add .
git commit -m ${VERSION}
git flow release publish
git flow release finish

git push --tags
git push

# be sure to push all branches
git checkout develop && git push
git checkout master && git push
```
