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

## Technologies utilisées

* Php: https://www.php.net/
* Symfony: https://symfony.com/
* MySQL: https://www.mysql.com/fr/
* Redis: https://redis.io/
* JQuery: https://jquery.com/
* Full Calendar: https://fullcalendar.io/docs
* Bootstrap: https://getbootstrap.com/
* Docker: https://www.docker.com/

## Configurer le projet

### Recaptcha Google

```shell
RECAPTCHA3_KEY=abcdefghijk
RECAPTCHA3_SECRET=6Ld9V1EaAAAAANvbtLUPODEB5aHT-8jb6BJ-vlvsabcdefghijk
RECAPTCHA3_ENABLED=1
```

### Google Agent (analytics)

```shell
GOOGLE_AGENT=UA-abcdefghijk-1
```

### Slmod Api Endpoint

```shell
API_SLMOD_URL=http://hostname:8080
```

### Teamspeak Api (WIP)

```shell
API_TEAMSPEAK_URL=serverquery://ts.veaf.org:10011/?server_port=9987
```

### Website mode

```shell
# veaf ou 51eg
WEBSITE=veaf
```

### Cdn - static assets

```shell
#CDN_URL=https://cdn.localhost/website
```

## Git flow

voir [release](doc/release.md)

## Tâches planifiées

### Planification

Exemple de planification, fichier /etc/cron.d/website:

```
*/20 * * * * debian /usr/local/bin/website-import-slmod-stats 2>&1 | ts >> /var/log/website/cron.log
*    * * * * debian /usr/local/bin/website-minly 2>&1 | ts >> /var/log/website/cron.log
```

### Import des stats SLMOD

Exemple import des stats SLMOD, fichier /usr/local/bin/website-import-slmod-stats:

```shell
#!/bin/env bash

echo "Import Slmod Stats"

pushd /home/debian/docker/website > /dev/null

/usr/local/bin/docker-compose exec -T -u www-data php ./bin/console app:slmod:import public 2>&1 | ts >> var/log/slmod-public.log
/usr/local/bin/docker-compose exec -T -u www-data php ./bin/console app:slmod:import private 2>&1 | ts >> var/log/slmod-private.log

popd > /dev/null
```

### Scan du serveur Team Speak

```shell
#!/bin/env bash

echo "Scan Team Speak"

pushd /home/debian/docker/website > /dev/null

/usr/local/bin/docker-compose exec -T -u www-data php ./bin/console app:team-speak:scan 2>&1 | ts >> var/log/team-speak.log

popd > /dev/null
```

### Rotation des logs

Exemple de rotation des logs, fichier /etc/logrotate.d/website:

```
/var/log/website/*.log {
  rotate 12
  monthly
  compress
  missingok
}

/home/debian/docker/website/var/log/*.log {
  rotate 12
  monthly
  compress
  missingok
}
```
