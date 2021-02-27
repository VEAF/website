<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210223184356 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE pe_DataRaw ADD id INT AUTO_INCREMENT NOT NULL, DROP PRIMARY KEY, ADD PRIMARY KEY (id)');
        $this->addSql('ALTER TABLE pe_DataRaw ADD CONSTRAINT FK_AEAAD734DC36465E FOREIGN KEY (pe_dataraw_instance) REFERENCES pe_OnlineStatus (pe_OnlineStatus_instance)');
        $this->addSql('CREATE INDEX IDX_AEAAD734DC36465E ON pe_DataRaw (pe_dataraw_instance)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE pe_DataRaw MODIFY id INT NOT NULL');
        $this->addSql('ALTER TABLE pe_DataRaw DROP FOREIGN KEY FK_AEAAD734DC36465E');
        $this->addSql('DROP INDEX IDX_AEAAD734DC36465E ON pe_DataRaw');
        $this->addSql('ALTER TABLE pe_DataRaw DROP PRIMARY KEY');
        $this->addSql('ALTER TABLE pe_DataRaw DROP id');
        $this->addSql('ALTER TABLE pe_DataRaw ADD PRIMARY KEY (pe_dataraw_type, pe_dataraw_instance)');
    }
}
