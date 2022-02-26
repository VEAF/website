<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220226184210 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE flight (id INT AUTO_INCREMENT NOT NULL, event_id INT NOT NULL, aircraft_id INT NOT NULL, name VARCHAR(32) NOT NULL, mission VARCHAR(255) DEFAULT NULL, nb_slots INT NOT NULL, INDEX IDX_C257E60E71F7E88B (event_id), INDEX IDX_C257E60E846E2F5C (aircraft_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE slot (id INT AUTO_INCREMENT NOT NULL, flight_id INT NOT NULL, user_id INT DEFAULT NULL, username VARCHAR(255) DEFAULT NULL, INDEX IDX_AC0E206791F478C5 (flight_id), INDEX IDX_AC0E2067A76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE flight ADD CONSTRAINT FK_C257E60E71F7E88B FOREIGN KEY (event_id) REFERENCES event (id)');
        $this->addSql('ALTER TABLE flight ADD CONSTRAINT FK_C257E60E846E2F5C FOREIGN KEY (aircraft_id) REFERENCES module (id)');
        $this->addSql('ALTER TABLE slot ADD CONSTRAINT FK_AC0E206791F478C5 FOREIGN KEY (flight_id) REFERENCES flight (id)');
        $this->addSql('ALTER TABLE slot ADD CONSTRAINT FK_AC0E2067A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE event ADD server_id INT DEFAULT NULL, ADD ato TINYINT(1) NOT NULL');
        $this->addSql('ALTER TABLE event ADD CONSTRAINT FK_3BAE0AA71844E6B7 FOREIGN KEY (server_id) REFERENCES server (id)');
        $this->addSql('CREATE INDEX IDX_3BAE0AA71844E6B7 ON event (server_id)');
        $this->addSql('ALTER TABLE server ADD atc TINYINT(1) NOT NULL, ADD gci TINYINT(1) NOT NULL');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE slot DROP FOREIGN KEY FK_AC0E206791F478C5');
        $this->addSql('DROP TABLE flight');
        $this->addSql('DROP TABLE slot');
        $this->addSql('ALTER TABLE event DROP FOREIGN KEY FK_3BAE0AA71844E6B7');
        $this->addSql('DROP INDEX IDX_3BAE0AA71844E6B7 ON event');
        $this->addSql('ALTER TABLE event DROP server_id, DROP ato');
        $this->addSql('ALTER TABLE server DROP atc, DROP gci');
    }
}
