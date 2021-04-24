<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210424083955 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('UPDATE pe_Config SET pe_Config_payload="v0.12.0" WHERE pe_Config_id = 1');
        $this->addSql('ALTER TABLE pe_OnlinePlayers ADD pe_OnlinePlayers_name VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE pe_OnlineStatus ADD pe_OnlineStatus_mission_theatre VARCHAR(255) DEFAULT NULL, ADD pe_OnlineStatus_mission_name VARCHAR(255) DEFAULT NULL, ADD pe_OnlineStatus_server_pause VARCHAR(255) DEFAULT NULL, ADD pe_OnlineStatus_mission_multiplayer VARCHAR(255) DEFAULT NULL, ADD pe_OnlineStatus_server_realtime VARCHAR(255) DEFAULT NULL, ADD pe_OnlineStatus_mission_modeltime VARCHAR(255) DEFAULT NULL, DROP pe_OnlineStatus_theatre, DROP pe_OnlineStatus_name, DROP pe_OnlineStatus_pause, DROP pe_OnlineStatus_multiplayer, DROP pe_OnlineStatus_realtime, DROP pe_OnlineStatus_modeltime, CHANGE pe_onlinestatus_players pe_OnlineStatus_server_players INT DEFAULT NULL');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE pe_OnlinePlayers DROP pe_OnlinePlayers_name');
        $this->addSql('ALTER TABLE pe_OnlineStatus ADD pe_OnlineStatus_theatre VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_unicode_ci`, ADD pe_OnlineStatus_name VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_unicode_ci`, ADD pe_OnlineStatus_pause VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_unicode_ci`, ADD pe_OnlineStatus_multiplayer VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_unicode_ci`, ADD pe_OnlineStatus_realtime VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_unicode_ci`, ADD pe_OnlineStatus_modeltime VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_unicode_ci`, DROP pe_OnlineStatus_mission_theatre, DROP pe_OnlineStatus_mission_name, DROP pe_OnlineStatus_server_pause, DROP pe_OnlineStatus_mission_multiplayer, DROP pe_OnlineStatus_server_realtime, DROP pe_OnlineStatus_mission_modeltime, CHANGE pe_onlinestatus_server_players pe_OnlineStatus_players INT DEFAULT NULL');
        $this->addSql('UPDATE pe_Config SET pe_Config_payload="v0.11.1" WHERE pe_Config_id = 1');
    }
}
