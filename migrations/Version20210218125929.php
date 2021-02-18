<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210218125929 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE player (id INT AUTO_INCREMENT NOT NULL, ucid VARCHAR(64) NOT NULL, nickname VARCHAR(128) DEFAULT NULL, join_at DATETIME NOT NULL, last_join_at DATETIME NOT NULL, UNIQUE INDEX ucid_idx (ucid), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE server (id INT AUTO_INCREMENT NOT NULL, perun_instance_id INT DEFAULT NULL, name VARCHAR(64) NOT NULL, code VARCHAR(64) NOT NULL, UNIQUE INDEX UNIQ_5A6DD5F6C5790928 (perun_instance_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE variant (id INT AUTO_INCREMENT NOT NULL, module_id INT DEFAULT NULL, code VARCHAR(32) NOT NULL, name VARCHAR(64) DEFAULT NULL, INDEX IDX_F143BFADAFC2B591 (module_id), UNIQUE INDEX code_idx (code), UNIQUE INDEX name_idx (name), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE variant_stat (id INT AUTO_INCREMENT NOT NULL, server_id INT NOT NULL, variant_id INT NOT NULL, player_id INT NOT NULL, total DOUBLE PRECISION NOT NULL, in_air DOUBLE PRECISION NOT NULL, INDEX IDX_CF4069B41844E6B7 (server_id), INDEX IDX_CF4069B43B69A9AF (variant_id), INDEX IDX_CF4069B499E6F5DF (player_id), UNIQUE INDEX variant_idx (server_id, variant_id, player_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE server ADD CONSTRAINT FK_5A6DD5F6C5790928 FOREIGN KEY (perun_instance_id) REFERENCES pe_OnlineStatus (pe_OnlineStatus_instance)');
        $this->addSql('ALTER TABLE variant ADD CONSTRAINT FK_F143BFADAFC2B591 FOREIGN KEY (module_id) REFERENCES module (id)');
        $this->addSql('ALTER TABLE variant_stat ADD CONSTRAINT FK_CF4069B41844E6B7 FOREIGN KEY (server_id) REFERENCES server (id)');
        $this->addSql('ALTER TABLE variant_stat ADD CONSTRAINT FK_CF4069B43B69A9AF FOREIGN KEY (variant_id) REFERENCES variant (id)');
        $this->addSql('ALTER TABLE variant_stat ADD CONSTRAINT FK_CF4069B499E6F5DF FOREIGN KEY (player_id) REFERENCES player (id)');
        $this->addSql('ALTER TABLE user ADD player_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE user ADD CONSTRAINT FK_8D93D64999E6F5DF FOREIGN KEY (player_id) REFERENCES player (id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_8D93D64999E6F5DF ON user (player_id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE user DROP FOREIGN KEY FK_8D93D64999E6F5DF');
        $this->addSql('ALTER TABLE variant_stat DROP FOREIGN KEY FK_CF4069B499E6F5DF');
        $this->addSql('ALTER TABLE variant_stat DROP FOREIGN KEY FK_CF4069B41844E6B7');
        $this->addSql('ALTER TABLE variant_stat DROP FOREIGN KEY FK_CF4069B43B69A9AF');
        $this->addSql('DROP TABLE player');
        $this->addSql('DROP TABLE server');
        $this->addSql('DROP TABLE variant');
        $this->addSql('DROP TABLE variant_stat');
        $this->addSql('DROP INDEX UNIQ_8D93D64999E6F5DF ON user');
        $this->addSql('ALTER TABLE user DROP player_id');
    }
}
