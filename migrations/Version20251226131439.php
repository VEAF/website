<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20251226131439 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Change event_vote.vote column type from INT to BOOLEAN to match PHP property type';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('ALTER TABLE event_vote CHANGE vote vote TINYINT(1) DEFAULT NULL');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('ALTER TABLE event_vote CHANGE vote vote INT DEFAULT NULL');
    }
}
