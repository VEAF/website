<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210210205251 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE user ADD password_request_token VARCHAR(255) DEFAULT NULL, ADD created_at DATETIME DEFAULT CURRENT_TIMESTAMP NOT NULL, ADD updated_at DATETIME DEFAULT CURRENT_TIMESTAMP NOT NULL, ADD sim_bms TINYINT(1) NOT NULL, ADD sim_dcs TINYINT(1) NOT NULL, ADD status INT DEFAULT NULL');
        $this->addSql('CREATE UNIQUE INDEX nickname_idx ON user (nickname)');
        $this->addSql('ALTER TABLE user RENAME INDEX uniq_8d93d649e7927c74 TO email_idx');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP INDEX nickname_idx ON user');
        $this->addSql('ALTER TABLE user DROP password_request_token, DROP created_at, DROP updated_at, DROP sim_bms, DROP sim_dcs, DROP status');
        $this->addSql('ALTER TABLE user RENAME INDEX email_idx TO UNIQ_8D93D649E7927C74');
    }
}
