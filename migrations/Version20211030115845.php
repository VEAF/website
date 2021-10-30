<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20211030115845 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE module_module_role (module_id INT NOT NULL, module_role_id INT NOT NULL, INDEX IDX_637EC5B4AFC2B591 (module_id), INDEX IDX_637EC5B47D781CB5 (module_role_id), PRIMARY KEY(module_id, module_role_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE module_module_system (module_id INT NOT NULL, module_system_id INT NOT NULL, INDEX IDX_3095910CAFC2B591 (module_id), INDEX IDX_3095910C8AF4306D (module_system_id), PRIMARY KEY(module_id, module_system_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE module_role (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, position INT NOT NULL, code VARCHAR(255) NOT NULL, UNIQUE INDEX code_idx (code), UNIQUE INDEX name_idx (name), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE module_system (id INT AUTO_INCREMENT NOT NULL, code VARCHAR(255) NOT NULL, name VARCHAR(255) NOT NULL, position INT NOT NULL, UNIQUE INDEX code_idx (code), UNIQUE INDEX name_idx (name), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE module_module_role ADD CONSTRAINT FK_637EC5B4AFC2B591 FOREIGN KEY (module_id) REFERENCES module (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE module_module_role ADD CONSTRAINT FK_637EC5B47D781CB5 FOREIGN KEY (module_role_id) REFERENCES module_role (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE module_module_system ADD CONSTRAINT FK_3095910CAFC2B591 FOREIGN KEY (module_id) REFERENCES module (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE module_module_system ADD CONSTRAINT FK_3095910C8AF4306D FOREIGN KEY (module_system_id) REFERENCES module_system (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE menu_item ADD restriction INT NOT NULL');
        $this->addSql('ALTER TABLE module ADD period INT DEFAULT NULL');
        $this->addSql('ALTER TABLE page ADD restriction INT NOT NULL');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE module_module_role DROP FOREIGN KEY FK_637EC5B47D781CB5');
        $this->addSql('ALTER TABLE module_module_system DROP FOREIGN KEY FK_3095910C8AF4306D');
        $this->addSql('DROP TABLE module_module_role');
        $this->addSql('DROP TABLE module_module_system');
        $this->addSql('DROP TABLE module_role');
        $this->addSql('DROP TABLE module_system');
        $this->addSql('ALTER TABLE menu_item DROP restriction');
        $this->addSql('ALTER TABLE module DROP period');
        $this->addSql('ALTER TABLE page DROP restriction');
    }
}
