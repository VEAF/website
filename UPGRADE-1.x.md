# upgrade notes

## upgrade to 1.8

add redis service, ex docker-compose.yml:

```yaml
redis:
  image: ${REDIS_REGISTRY:-redis}:${REDIS_TAG:-6-buster}
  hostname: redis
  networks:
    - php
```

and configure redis connection, to your .php.env:

```dotenv
REDIS_URL=redis://redis
```

add a cron job every minutes:

```shell
/usr/local/bin/docker-compose exec -T -u www-data php ./bin/console app:team-speak:scan 2>&1 | ts >> var/log/team-speak.log
```