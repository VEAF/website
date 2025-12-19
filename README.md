# VEAF Website

## Travailler sur le projet

Prérequis:
* docker
* docker-compose
* git
* un reverse proxy

```shell
git clone https://github.com/VEAF/website.git
# or ssh version
# git clone git@github.com:VEAF/website.git

cd website
./scripts/upgrade.sh
```

et charger les fixtures:

```shell
touch .fixtures
./scripts/dev/fixtures.sh
```

Accès par défaut:
* [http://veaf.localhost](http://veaf.localhost): mitch@localhost / test1234
* [http://pma.veaf.localhost](http://pma.veaf.localhost): root / test (base website)

## Commandes disponibles

Tous les scripts sont dans le répertoire `./scripts/` et acceptent l'option `--help`.

### Gestion Docker

| Commande | Description |
|----------|-------------|
| `./scripts/upgrade.sh` | Mise à jour complète (pull, up, composer, migrations) |
| `./scripts/pull.sh` | Pull des images Docker |
| `./scripts/up.sh` | Démarrer les conteneurs |
| `./scripts/start.sh` | Alias de up.sh |
| `./scripts/stop.sh` | Arrêter les conteneurs |
| `./scripts/restart.sh` | Redémarrer les conteneurs |
| `./scripts/down.sh` | Supprimer les conteneurs et volumes |
| `./scripts/logs.sh` | Afficher les logs |
| `./scripts/ps.sh` | Statut des conteneurs |
| `./scripts/check.sh` | Vérifier les versions des services |

### Accès aux conteneurs

| Commande | Description |
|----------|-------------|
| `./scripts/php.sh` | Shell dans le conteneur PHP (www-data) |
| `./scripts/php.sh --root` | Shell dans le conteneur PHP (root) |
| `./scripts/nginx.sh` | Shell dans le conteneur Nginx |

### Développement

| Commande | Description |
|----------|-------------|
| `./scripts/cc.sh` | Vider le cache Symfony |
| `./scripts/fix.sh` | Lancer PHP CS Fixer sur src/ |
| `./scripts/dev/fixtures.sh` | Charger les fixtures (dev uniquement) |

## Pour mettre à jour le projet en production

```shell
cd website
./scripts/upgrade.sh
```

Options disponibles pour `upgrade.sh`:
* `--no-docker-pull` : ne pas pull les images
* `--no-docker-up` : ne pas démarrer les conteneurs
* `--no-git-pull` : ne pas pull les sources
* `--no-composer` : ne pas installer les dépendances
* `--no-migrations` : ne pas lancer les migrations

## Technologies utilisées

* Php: https://www.php.net/
* Symfony: https://symfony.com/
* MySQL: https://www.mysql.com/fr/
* Redis: https://redis.io/
* JQuery: https://jquery.com/
* Full Calendar: https://fullcalendar.io/docs
* Bootstrap: https://getbootstrap.com/
* Bootstrap Theme: https://bootswatch.com/cerulean/
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

### Gestion des tâches automatisées du calendrier

```shell
#!/bin/env bash

echo "Calendar"

pushd /home/debian/docker/website > /dev/null

/usr/local/bin/docker-compose exec -T -u www-data php ./bin/console app:calendar:event:auto 2>&1 | ts >> var/log/calendar.log

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
