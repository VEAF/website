<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210312202243 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE menu_item (id INT AUTO_INCREMENT NOT NULL, menu_id INT DEFAULT NULL, url_id INT DEFAULT NULL, page_id INT DEFAULT NULL, label VARCHAR(64) DEFAULT NULL, type INT NOT NULL, icon VARCHAR(64) DEFAULT NULL, theme_classes VARCHAR(255) DEFAULT NULL, enabled TINYINT(1) NOT NULL, position INT NOT NULL, link VARCHAR(255) DEFAULT NULL, INDEX IDX_D754D550CCD7E912 (menu_id), INDEX IDX_D754D55081CFDAE7 (url_id), INDEX IDX_D754D550C4663E4 (page_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE menu_item ADD CONSTRAINT FK_D754D550CCD7E912 FOREIGN KEY (menu_id) REFERENCES menu_item (id)');
        $this->addSql('ALTER TABLE menu_item ADD CONSTRAINT FK_D754D55081CFDAE7 FOREIGN KEY (url_id) REFERENCES url (id)');
        $this->addSql('ALTER TABLE menu_item ADD CONSTRAINT FK_D754D550C4663E4 FOREIGN KEY (page_id) REFERENCES page (id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE menu_item DROP FOREIGN KEY FK_D754D550CCD7E912');
        $this->addSql('DROP TABLE menu_item');
    }
}
