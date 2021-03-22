<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210320115453 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE recruitment_event (id INT AUTO_INCREMENT NOT NULL, user_id INT NOT NULL, validator_id INT DEFAULT NULL, created_at DATETIME NOT NULL, updated_at DATETIME NOT NULL, event_at DATETIME NOT NULL, type INT NOT NULL, ack_at DATETIME DEFAULT NULL, comment VARCHAR(255) DEFAULT NULL, INDEX IDX_D1195597A76ED395 (user_id), INDEX IDX_D1195597B0644AEC (validator_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE recruitment_event ADD CONSTRAINT FK_D1195597A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE recruitment_event ADD CONSTRAINT FK_D1195597B0644AEC FOREIGN KEY (validator_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE user ADD need_presentation TINYINT(1) NOT NULL, ADD cadet_flights INT NOT NULL');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE recruitment_event');
        $this->addSql('ALTER TABLE user DROP need_presentation, DROP cadet_flights');
    }
}
