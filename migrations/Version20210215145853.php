<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210215145853 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE pe_LogEvent ADD CONSTRAINT FK_25F0A47E64571713 FOREIGN KEY (pe_LogEvent_missionhash_id) REFERENCES pe_DataMissionHashes (pe_DataMissionHashes_id)');
        $this->addSql('ALTER TABLE pe_LogEvent RENAME INDEX pe_logevent_missionhash_id TO IDX_25F0A47E64571713');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE pe_LogEvent DROP FOREIGN KEY FK_25F0A47E64571713');
        $this->addSql('ALTER TABLE pe_LogEvent RENAME INDEX idx_25f0a47e64571713 TO pe_LogEvent_missionhash_id');
    }
}
