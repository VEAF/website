#!/usr/bin/env bash
# upgrade project

source $(dirname $0)/.include.sh

# break on first error
set -e

function display_help()
{
    echo -e "${COLOR_GREEN}upgrade.sh${COLOR_DEFAULT}"
    echo -e "  ${COLOR_GREEN}--no-docker-pull${COLOR_DEFAULT}   to avoid docker pull images"
    echo -e "  ${COLOR_GREEN}--no-docker-up${COLOR_DEFAULT}     to avoid docker up"
    echo -e "  ${COLOR_GREEN}--no-git-pull${COLOR_DEFAULT}      to avoid git pull on current branch"
    echo -e "  ${COLOR_GREEN}--no-composer${COLOR_DEFAULT}      to avoid composer install"
    echo -e "  ${COLOR_GREEN}--no-dev${COLOR_DEFAULT}           to avoid composer install dev dependencies"
    echo -e "  ${COLOR_GREEN}--no-scripts${COLOR_DEFAULT}       to avoid composer launch warmup scripts"
    echo -e "  ${COLOR_GREEN}--no-migrations${COLOR_DEFAULT}    to avoid doctrine migrations"
}

# special variable to count elapsed time
SECONDS=0

# default values
WITH_DOCKER_PULL=1
WITH_DOCKER_UP=1
WITH_GIT_PULL=1
WITH_COMPOSER=1
WITH_COMPOSER_DEV=1
WITH_COMPOSER_SCRIPTS=1
WITH_MIGRATIONS=1

DOCKER_PHP_COMMAND="docker-compose exec -u www-data php"

# parse command line
POSITIONAL=()
while [[ $# -gt 0 ]]
do
key="$1"

case $key in
    --no-migrations)
    WITH_MIGRATIONS=0
    shift # past argument
    ;;
    --no-docker-pull)
    WITH_DOCKER_PULL=0
    shift # past argument
    ;;
    --no-docker-up)
    WITH_DOCKER_UP=0
    shift # past argument
    ;;
    --no-git-pull)
    WITH_GIT_PULL=0
    shift # past argument
    ;;
    --no-composer)
    WITH_COMPOSER=0
    shift # past argument
    ;;
    --no-dev)
    WITH_COMPOSER_DEV=0
    shift # past argument
    ;;
    --no-scripts)
    WITH_COMPOSER_SCRIPTS=0
    shift # past argument
    ;;
    *)    # unknown option
    echo -e "unknown option ${COLOR_BLUE}${key}${COLOR_DEFAULT}"
    display_help
    exit 1
    ;;
esac
done

# script entry
echo -e "${COLOR_GREEN}upgrade is starting${COLOR_DEFAULT}"

# check context
if [ -f /.dockerenv ]; then
    echo -e "${COLOR_RED}Error: ${COLOR_GREEN}you can't launch upgrade process inside a container${COLOR_DEFAULT}"
    exit 2
fi

if [ ${WITH_DOCKER_PULL} -ne 0 ];
then
    echo -e "${COLOR_GREEN}pulling docker images${COLOR_DEFAULT}"
    docker-compose pull --no-parallel
fi

if [ ${WITH_DOCKER_UP} -ne 0 ];
then
    echo -e "${COLOR_GREEN}starting docker containers${COLOR_DEFAULT}"
    docker-compose up -d
fi

if [ ${WITH_GIT_PULL} -ne 0 ];
then
    echo -e "${COLOR_GREEN}pulling sources from current branch${COLOR_DEFAULT}"
    git pull
fi

if [ ${WITH_COMPOSER} -ne 0 ];
then
    COMPOSER_ARGS=""
    if [ "${APP_ENV}" == 'prod' ];
    then
        COMPOSER_ARGS="${COMPOSER_ARGS} --no-dev"
    else
        if [ ${WITH_COMPOSER_DEV} -eq 0 ];
        then
            COMPOSER_ARGS="${COMPOSER_ARGS} --no-dev"
        fi
    fi
    if [ ${WITH_COMPOSER_SCRIPTS} -eq 0 ];
    then
        COMPOSER_ARGS="${COMPOSER_ARGS} --no-scripts"
    fi
    echo -e "${COLOR_GREEN}installing composer dependencies${COLOR_DEFAULT}"
    ${DOCKER_PHP_COMMAND} composer install ${COMPOSER_ARGS}
fi

if [ ${WITH_MIGRATIONS} -ne 0 ];
then
    echo -e "${COLOR_GREEN}running migrations${COLOR_DEFAULT}"
    ${DOCKER_PHP_COMMAND} ./bin/console doctrine:migrations:migrate -n
fi

echo -e "${COLOR_GREEN}upgrade done in ${COLOR_BLUE}$SECONDS ${COLOR_GREEN}seconds${COLOR_DEFAULT}"
