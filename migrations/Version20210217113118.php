<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210217113118 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE module ADD image_header_id INT DEFAULT NULL, ADD image_id INT DEFAULT NULL, ADD landing_page TINYINT(1) NOT NULL, ADD landing_page_number INT NOT NULL');
        $this->addSql('ALTER TABLE module ADD CONSTRAINT FK_C242628F67BC48C FOREIGN KEY (image_header_id) REFERENCES file (id)');
        $this->addSql('ALTER TABLE module ADD CONSTRAINT FK_C2426283DA5256D FOREIGN KEY (image_id) REFERENCES file (id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_C242628F67BC48C ON module (image_header_id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_C2426283DA5256D ON module (image_id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE module DROP FOREIGN KEY FK_C242628F67BC48C');
        $this->addSql('ALTER TABLE module DROP FOREIGN KEY FK_C2426283DA5256D');
        $this->addSql('DROP INDEX UNIQ_C242628F67BC48C ON module');
        $this->addSql('DROP INDEX UNIQ_C2426283DA5256D ON module');
        $this->addSql('ALTER TABLE module DROP image_header_id, DROP image_id, DROP landing_page, DROP landing_page_number');
    }
}
