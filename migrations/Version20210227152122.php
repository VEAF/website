<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210227152122 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE event (id INT AUTO_INCREMENT NOT NULL, map_id INT DEFAULT NULL, image_id INT DEFAULT NULL, owner_id INT NOT NULL, created_at DATETIME NOT NULL, updated_at DATETIME NOT NULL, start_date DATETIME NOT NULL, end_date DATETIME NOT NULL, type INT NOT NULL, sim_dcs TINYINT(1) NOT NULL, sim_bms TINYINT(1) NOT NULL, title VARCHAR(255) NOT NULL, description LONGTEXT DEFAULT NULL, restrictions LONGTEXT DEFAULT NULL COMMENT \'(DC2Type:simple_array)\', deleted TINYINT(1) NOT NULL, deleted_at DATETIME DEFAULT NULL, INDEX IDX_3BAE0AA753C55F64 (map_id), INDEX IDX_3BAE0AA73DA5256D (image_id), INDEX IDX_3BAE0AA77E3C61F9 (owner_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE event_module (event_id INT NOT NULL, module_id INT NOT NULL, INDEX IDX_3EBD517A71F7E88B (event_id), INDEX IDX_3EBD517AAFC2B591 (module_id), PRIMARY KEY(event_id, module_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE event_vote (id INT AUTO_INCREMENT NOT NULL, user_id INT NOT NULL, event_id INT NOT NULL, created_at DATETIME NOT NULL, updated_at DATETIME NOT NULL, vote INT DEFAULT NULL, comment VARCHAR(255) DEFAULT NULL, INDEX IDX_45DBC9CFA76ED395 (user_id), INDEX IDX_45DBC9CF71F7E88B (event_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE event ADD CONSTRAINT FK_3BAE0AA753C55F64 FOREIGN KEY (map_id) REFERENCES module (id)');
        $this->addSql('ALTER TABLE event ADD CONSTRAINT FK_3BAE0AA73DA5256D FOREIGN KEY (image_id) REFERENCES file (id)');
        $this->addSql('ALTER TABLE event ADD CONSTRAINT FK_3BAE0AA77E3C61F9 FOREIGN KEY (owner_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE event_module ADD CONSTRAINT FK_3EBD517A71F7E88B FOREIGN KEY (event_id) REFERENCES event (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE event_module ADD CONSTRAINT FK_3EBD517AAFC2B591 FOREIGN KEY (module_id) REFERENCES module (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE event_vote ADD CONSTRAINT FK_45DBC9CFA76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE event_vote ADD CONSTRAINT FK_45DBC9CF71F7E88B FOREIGN KEY (event_id) REFERENCES event (id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE event_module DROP FOREIGN KEY FK_3EBD517A71F7E88B');
        $this->addSql('ALTER TABLE event_vote DROP FOREIGN KEY FK_45DBC9CF71F7E88B');
        $this->addSql('DROP TABLE event');
        $this->addSql('DROP TABLE event_module');
        $this->addSql('DROP TABLE event_vote');
    }
}
