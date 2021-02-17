#!/usr/bin/env bash
set -e

# merge pending CHANGELOG files into final CHANGELOG.md file
# @param $1 release version (ex: 1.2.3)
VERSION=$1
if [[ -z "${VERSION}" ]]
then
  echo "syntax: ./scripts/changelog.sh VERSION"
  exit 1
fi

if [[ ! -f "CHANGELOG.md" ]]
then
  echo "CHANGELOG.md not exists"
  exit 2
fi

CHANGELOG_FILES=$(ls CHANGELOG?*.md)

echo "### ${VERSION}" > CHANGELOG.md.tmp

for entry in ${CHANGELOG_FILES}
do
  # add a new line at the end of the file if it's not the case
  sed -i -e '$a\' "${entry}"
  echo "appending ${entry} to CHANGELOG.md"
  cat "${entry}" >> CHANGELOG.md.tmp

done

echo "" > CHANGELOG.md.tmp
echo "keep original changes"
cat CHANGELOG.md >> CHANGELOG.md.tmp

echo "finalize CHANGELOG"
cat CHANGELOG.md.tmp > CHANGELOG.md
rm CHANGELOG?*.md CHANGELOG.md.tmp
