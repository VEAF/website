<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210314105416 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE weapon (id INT AUTO_INCREMENT NOT NULL, code VARCHAR(64) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE weapon_stat (id INT AUTO_INCREMENT NOT NULL, weapon_id INT NOT NULL, variant_stat_id INT NOT NULL, num_hits INT NOT NULL, shot INT NOT NULL, hit INT NOT NULL, kills INT NOT NULL, gun TINYINT(1) NOT NULL, INDEX IDX_98A717DE95B82273 (weapon_id), INDEX IDX_98A717DE66F5024D (variant_stat_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE weapon_stat ADD CONSTRAINT FK_98A717DE95B82273 FOREIGN KEY (weapon_id) REFERENCES weapon (id)');
        $this->addSql('ALTER TABLE weapon_stat ADD CONSTRAINT FK_98A717DE66F5024D FOREIGN KEY (variant_stat_id) REFERENCES variant_stat (id)');
        $this->addSql('ALTER TABLE variant_stat ADD kills_ground_units_total INT NOT NULL, ADD kills_buildings_total INT NOT NULL, ADD kills_planes_total INT NOT NULL, ADD kills_helicopters_total INT NOT NULL, ADD kills_ships_total INT NOT NULL, ADD landing_total INT NOT NULL, ADD takeoff_total INT NOT NULL, ADD losses_pilot_death INT NOT NULL, ADD losses_crash INT NOT NULL, ADD losses_eject INT NOT NULL');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE weapon_stat DROP FOREIGN KEY FK_98A717DE95B82273');
        $this->addSql('DROP TABLE weapon');
        $this->addSql('DROP TABLE weapon_stat');
        $this->addSql('ALTER TABLE variant_stat DROP kills_ground_units_total, DROP kills_buildings_total, DROP kills_planes_total, DROP kills_helicopters_total, DROP kills_ships_total, DROP landing_total, DROP takeoff_total, DROP losses_pilot_death, DROP losses_crash, DROP losses_eject');
    }
}
