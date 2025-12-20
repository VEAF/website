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
 * - Création du schéma SQLite en mémoire
 * - Helpers pour accéder aux services
 * - Nettoyage après chaque test
 */
abstract class AbstractIntegrationTestCase extends KernelTestCase
{
    protected EntityManagerInterface $entityManager;

    protected function setUp(): void
    {
        self::bootKernel();

        $this->entityManager = self::getContainer()->get('doctrine')->getManager();

        // Créer le schéma SQLite en mémoire
        $this->createSchema();
    }

    protected function tearDown(): void
    {
        parent::tearDown();

        // Fermer la connexion pour éviter les fuites
        if (isset($this->entityManager)) {
            $this->entityManager->close();
        }
    }

    /**
     * Crée le schéma de base de données en mémoire.
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
