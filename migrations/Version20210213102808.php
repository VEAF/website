<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210213102808 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE user_module (id INT AUTO_INCREMENT NOT NULL, user_id INT NOT NULL, module_id INT NOT NULL, active TINYINT(1) NOT NULL, level INT NOT NULL, INDEX IDX_69763D15A76ED395 (user_id), INDEX IDX_69763D15AFC2B591 (module_id), UNIQUE INDEX usermodule_idx (user_id, module_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE user_module ADD CONSTRAINT FK_69763D15A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE user_module ADD CONSTRAINT FK_69763D15AFC2B591 FOREIGN KEY (module_id) REFERENCES module (id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE user_module');
    }
}
