<?php

namespace App\Tests\Integration;

use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Tools\SchemaTool;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

/**
 * Classe de base pour les tests d'intégration.
 *
 * Fournit:
 * - Boot automatique du kernel Symfony
 * - Création du schéma MySQL (une seule fois par suite de tests)
 * - Isolation via transactions avec rollback
 * - Helpers pour accéder aux services
 */
abstract class AbstractIntegrationTestCase extends KernelTestCase
{
    protected EntityManagerInterface $entityManager;

    private static bool $schemaCreated = false;

    protected function setUp(): void
    {
        self::bootKernel();

        $this->entityManager = self::getContainer()->get('doctrine')->getManager();

        // Créer le schéma une seule fois par suite de tests
        if (!self::$schemaCreated) {
            $this->createSchema();
            self::$schemaCreated = true;
        }

        // Démarrer une transaction pour l'isolation
        $this->entityManager->beginTransaction();
    }

    protected function tearDown(): void
    {
        // Rollback pour annuler toutes les modifications
        if ($this->entityManager->getConnection()->isTransactionActive()) {
            $this->entityManager->rollback();
        }

        $this->entityManager->close();

        parent::tearDown();
    }

    /**
     * Crée le schéma de base de données.
     */
    private function createSchema(): void
    {
        $metadata = $this->entityManager->getMetadataFactory()->getAllMetadata();

        if (!empty($metadata)) {
            $schemaTool = new SchemaTool($this->entityManager);
            $schemaTool->dropSchema($metadata);
            $schemaTool->createSchema($metadata);
        }
    }

    /**
     * Helper pour obtenir un service du container.
     *
     * @template T
     *
     * @param class-string<T> $id
     *
     * @return T
     */
    protected function getService(string $id): object
    {
        return self::getContainer()->get($id);
    }

    /**
     * Helper pour persister et flush une entité.
     */
    protected function persistAndFlush(object $entity): void
    {
        $this->entityManager->persist($entity);
        $this->entityManager->flush();
    }

    /**
     * Helper pour vider le cache de l'entity manager.
     */
    protected function clearEntityManager(): void
    {
        $this->entityManager->clear();
    }
}
