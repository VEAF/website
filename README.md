# VEAF Website

## Travailler sur le projet

Prérequis:
* docker
* docker-compose
* git
* make
* un reverse proxy

```shell
git clone https://github.com/VEAF/website.git
# or ssh version
# git clone git@github.com:VEAF/website.git

cd website
make upgrade  
```

et charger les fixtures:

```shell
make fixtures  
```

Accès par défaut:
* [http://veaf.localhost](http://veaf.localhost): mitch@localhost / test1234
* [http://pma.veaf.localhost](http://pma.veaf.localhost): root / test (base website)

## Pour mettre à jour le projet en production

```shell
cd website
./scripts/upgrade.sh
```

## Git flow

```shell
read VERSION
git checkout develop
git pull
git flow release start ${VERSION}
sed -i "/  app_version:/c\  app_version: ${VERSION}" config/packages/parameters.yaml

./scripts/changelog.sh ${VERSION}
git add .
git commit -m ${VERSION}
git flow release publish
git flow release finish

git push --tags
git push
git checkout master && git push
```
