# CLAUDE.md

This file provides guidance to Claude Code (claude.ai/code) when working with code in this repository.

## Project Overview

VEAF Website is a Symfony 4.4 PHP application for the Virtual European Air Force (VEAF) flight simulation community. It can also be configured for 51ème Escadron Griffon (51eg) via the `WEBSITE` environment variable.

## Development Commands

All commands use Docker and are run via shell scripts in `./scripts/`:

```bash
./scripts/upgrade.sh       # Pull images, start containers, install composer deps, run migrations
./scripts/dev/fixtures.sh  # Load test fixtures (requires `touch .fixtures` safety file)
./scripts/up.sh            # Start all containers
./scripts/stop.sh          # Stop all containers
./scripts/php.sh           # Shell into PHP container as www-data
./scripts/cc.sh            # Clear Symfony cache
./scripts/fix.sh           # Run PHP CS Fixer on src/
```

All scripts support `--help` for detailed usage information.

Console commands inside the PHP container:
```bash
./bin/console doctrine:migrations:migrate -n    # Run migrations
./bin/console cache:clear                       # Clear cache
./bin/console hautelook:fixtures:load -n        # Load fixtures
```

## Architecture

### Dual Database Structure
The application uses two separate database connections:
- **Main database**: Application entities in `src/Entity/`
- **Perun database**: DCS World server statistics from Perun mod in `src/Perun/Entity/`

Both are configured in `config/packages/doctrine.yaml` with separate Doctrine mappings.

### Source Code Organization (`src/`)

- **Controller/**: Web controllers, with `Admin/` subdirectory for admin panel
- **Entity/**: Doctrine entities (User, Player, Calendar events, Modules, Pages, etc.)
- **Perun/**: Self-contained module for Perun DCS stats (Entity/, DTO/, Repository/, Service/)
- **Manager/**: Business logic layer (UserManager, Calendar/EventManager, etc.)
- **Service/**: Services for external integrations (SlmodImportService, TeamSpeak3Client, MapService)
- **DTO/**: Data transfer objects for stats and external data
- **Form/**: Symfony form types
- **Repository/**: Doctrine repositories

### Multi-Website Theming
Templates support multiple website instances via `templates/website/`:
- `veaf/` - Virtual European Air Force theme
- `51eg/` - 51ème Escadron Griffon theme

Set via `WEBSITE` environment variable.

### Key Domain Concepts
- **Modules**: Aircraft/equipment types that users can be qualified on
- **Calendar Events**: Flight events with slots, ATOs, and voting
- **Players**: DCS World player profiles linked to website users
- **Servers**: DCS server instances with stats integration
- **Perun**: External DCS stats tracking system with its own entity model

## Git Flow

The project uses git-flow with `develop` as the integration branch and `master` for releases. See `doc/release.md` for release process.

## Docker Services

- **php**: PHP-FPM with Symfony application
- **nginx**: Web server
- **mysql**: MySQL 5.7 database
- **redis**: Session/cache storage
- **phpmyadmin**: Database administration (dev only)

Default dev access: http://veaf.localhost (mitch@localhost / test1234)
