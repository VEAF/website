# CLAUDE.md

This file provides guidance to Claude Code (claude.ai/code) when working with code in this repository.

## Project Overview

VEAF Website is a Symfony PHP application for the Virtual European Air Force (VEAF) flight simulation community. It was also configurable for 51ème Escadron Griffon (51eg) via the `WEBSITE` environment variable.

## Development Commands

All commands use Docker and are run via shell scripts in `./scripts/`:

```bash
./scripts/upgrade.sh       # Pull images, start containers, install composer deps, run migrations
./scripts/dev/fixtures.sh  # Load test fixtures (requires `touch .fixtures` safety file)
./scripts/start.sh         # Start all containers
./scripts/stop.sh          # Stop all containers
./scripts/php.sh           # Shell into PHP container as www-data (or run a command)
./scripts/console.sh       # Run Symfony console commands
./scripts/cc.sh            # Clear Symfony cache
./scripts/fix.sh           # Run PHP CS Fixer on src/
./scripts/dev/test.sh      # Run tests
```

All scripts support `--help` for detailed usage information.

Console commands (via `./scripts/console.sh`):
```bash
./scripts/console.sh doctrine:migrations:migrate -n    # Run migrations
./scripts/console.sh cache:clear                       # Clear cache
./scripts/console.sh hautelook:fixtures:load -n        # Load fixtures
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

## Tests

### Running Tests

```bash
./scripts/dev/test.sh                    # Run all tests
./scripts/dev/test.sh tests/Unit         # Run only unit tests
./scripts/dev/test.sh tests/Integration  # Run only integration tests
./scripts/dev/test.sh tests/Functional   # Run only functional tests
./scripts/dev/test.sh --filter=MethodName  # Run specific test method
```

### Test Structure (`tests/`)

The project uses PHPUnit with three test suites organized by scope:

```
tests/
├── Unit/                    # Tests unitaires (pas de dépendances externes)
│   └── Service/             # Miroir de src/Service/
├── Integration/             # Tests avec base de données
│   ├── AbstractIntegrationTestCase.php  # Classe de base
│   └── Service/
└── Functional/              # Tests HTTP/contrôleurs
    └── Controller/
```

### Test Types and Conventions

**Unit Tests** (`tests/Unit/`)
- Héritent de `PHPUnit\Framework\TestCase`
- Pas de dépendances externes (DB, services Symfony)
- Utilisent des mocks pour les dépendances
- Rapides à exécuter

**Integration Tests** (`tests/Integration/`)
- Héritent de `AbstractIntegrationTestCase`
- Accès à la base de données (schéma créé automatiquement)
- Isolation via transactions avec rollback automatique
- Helpers disponibles: `getService()`, `persistAndFlush()`, `clearEntityManager()`

**Functional Tests** (`tests/Functional/`)
- Héritent de `KernelTestCase` ou `WebTestCase`
- Testent les routes, contrôleurs, réponses HTTP

### Naming Conventions

- Fichiers: `{ClassToTest}Test.php` (ex: `ProjectionServiceTest.php`)
- Namespace: Miroir de `src/` avec préfixe `App\Tests\` (ex: `App\Tests\Unit\Service\`)
- Méthodes: `test{WhatIsTested}{ExpectedBehavior}()` en camelCase
  - Ex: `testGetNextEventDateTimeReturnsNullForNoRepeat()`
  - Ex: `testUnsupportedTheatreThrowsException()`

### Writing Tests

```php
// Unit test example
class MyServiceTest extends TestCase
{
    private MyService $service;

    protected function setUp(): void
    {
        $dependency = $this->createMock(DependencyInterface::class);
        $this->service = new MyService($dependency);
    }

    public function testMethodReturnsExpectedValue(): void
    {
        $result = $this->service->doSomething();
        $this->assertEquals('expected', $result);
    }

    /**
     * @dataProvider dataProviderName
     */
    public function testWithDataProvider(string $input, string $expected): void
    {
        $this->assertEquals($expected, $this->service->process($input));
    }

    public function dataProviderName(): array
    {
        return [
            'case name' => ['input', 'expected'],
        ];
    }
}

// Integration test example
class MyRepositoryTest extends AbstractIntegrationTestCase
{
    public function testFindByStatus(): void
    {
        $entity = new Entity();
        $this->persistAndFlush($entity);

        $repository = $this->getService(EntityRepository::class);
        $result = $repository->findByStatus('active');

        $this->assertCount(1, $result);
    }
}
```

## Git Flow

The project uses git-flow with `develop` as the integration branch and `master` for releases. See `doc/release.md` for release process.

## Docker Services

- **php**: PHP-FPM with Symfony application
- **nginx**: Web server
- **mysql**: MySQL 5.7 database
- **redis**: Session/cache storage
- **phpmyadmin**: Database administration (dev only)

Default dev access: http://veaf.localhost (mitch@localhost / test1234)
