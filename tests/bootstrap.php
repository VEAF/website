<?php

use Symfony\Component\Dotenv\Dotenv;

require dirname(__DIR__).'/vendor/autoload.php';

// =============================================================================
// SÉCURITÉ ANTI-PRODUCTION
// =============================================================================

$appEnv = $_ENV['APP_ENV'] ?? $_SERVER['APP_ENV'] ?? 'dev';

// Vérification 1: Interdire l'exécution en production
if ('prod' === $appEnv) {
    throw new \RuntimeException(
        'FATAL: Tests cannot be executed in production environment! '.
        'Current APP_ENV is "prod". Set APP_ENV=test before running tests.'
    );
}

// Vérification 2: Fichier de sécurité requis pour TOUS les tests
$projectRoot = dirname(__DIR__);
$testSafetyFile = $projectRoot.'/.tests';

if (!file_exists($testSafetyFile)) {
    throw new \RuntimeException(
        'SAFETY: Tests require a ".tests" file in the project root. '.
        'This is a safety measure to prevent accidental execution in production. '.
        'Create it with: touch .tests'
    );
}

// =============================================================================
// BOOTSTRAP SYMFONY
// =============================================================================

if (file_exists(dirname(__DIR__).'/config/bootstrap.php')) {
    require dirname(__DIR__).'/config/bootstrap.php';
} elseif (method_exists(Dotenv::class, 'bootEnv')) {
    (new Dotenv())->bootEnv(dirname(__DIR__).'/.env');
}
