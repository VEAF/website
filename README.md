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
