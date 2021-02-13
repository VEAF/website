#!/usr/bin/env bash
# load fixtures (dev only !) without running migrations

source $(dirname $0)/../.include.sh

# break on first error
set -e

# default values
WITH_MIGRATIONS=0
WITH_FIXTURES=1

# parse command line
POSITIONAL=()
while [[ $# -gt 0 ]]
do
key="$1"

case $key in
    --with-migrations)
    WITH_MIGRATIONS=1
    shift # past argument
    ;;
    --without-fixtures)
    WITH_FIXTURES=0
    shift # past argument
    ;;
    *)    # unknown option
    echo -e "unknown option ${COLOR_BLUE}${key}${COLOR_DEFAULT}"
    exit 1
    ;;
esac
done

# script entry

echo -e "${COLOR_RED}Loading fixtures - never launch this script in PROD environment"

if [ "${APP_ENV}" == "prod" ];
then
    echo -e "${COLOR_RED}You are not prod environment, loading fixtures is forbidden"
    exit 2
fi

if [ ! -f ".fixtures" ];
then
    echo -e "${COLOR_RED}Fixtures are disabled${COLOR_DEFAULT}, use ${COLOR_BLUE}touch .fixtures${COLOR_DEFAULT} to be able to load fixtures"
    exit 3
fi

./bin/console doctrine:database:drop --force
./bin/console doctrine:database:create

if [ ${WITH_MIGRATIONS} -ne 0 ];
then
    echo -e "${COLOR_GREEN}running migrations${COLOR_DEFAULT}"
    ./bin/console doctrine:migrations:migrate -n
else
    echo -e "${COLOR_GREEN}running without migrations${COLOR_DEFAULT}, be aware to not generate migrations (inconsistent mode)"
    ./bin/console doctrine:schema:update --force
fi

if [ ${WITH_FIXTURES} -ne 0 ];
then
    ./bin/console hautelook:fixtures:load -n
fi
