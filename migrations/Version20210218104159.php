<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210218104159 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE user DROP FOREIGN KEY FK_8D93D64999E6F5DF');
        $this->addSql('DROP INDEX UNIQ_8D93D64999E6F5DF ON user');
        $this->addSql('ALTER TABLE user CHANGE player_id perun_player_id BIGINT DEFAULT NULL');
        $this->addSql('ALTER TABLE user ADD CONSTRAINT FK_8D93D6498AE286AE FOREIGN KEY (perun_player_id) REFERENCES pe_DataPlayers (pe_DataPlayers_id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_8D93D6498AE286AE ON user (perun_player_id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE user DROP FOREIGN KEY FK_8D93D6498AE286AE');
        $this->addSql('DROP INDEX UNIQ_8D93D6498AE286AE ON user');
        $this->addSql('ALTER TABLE user CHANGE perun_player_id player_id BIGINT DEFAULT NULL');
        $this->addSql('ALTER TABLE user ADD CONSTRAINT FK_8D93D64999E6F5DF FOREIGN KEY (player_id) REFERENCES pe_DataPlayers (pe_DataPlayers_id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_8D93D64999E6F5DF ON user (player_id)');
    }
}
