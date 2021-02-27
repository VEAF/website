<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210227165636 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE pe_OnlinePlayers DROP FOREIGN KEY FK_676CFD2FB0467609');
        $this->addSql('DROP INDEX IDX_676CFD2FB0467609 ON pe_OnlinePlayers');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE pe_OnlinePlayers ADD CONSTRAINT FK_676CFD2FB0467609 FOREIGN KEY (pe_OnlinePlayers_id) REFERENCES pe_DataPlayers (pe_DataPlayers_id)');
        $this->addSql('CREATE INDEX IDX_676CFD2FB0467609 ON pe_OnlinePlayers (pe_OnlinePlayers_id)');
    }
}
