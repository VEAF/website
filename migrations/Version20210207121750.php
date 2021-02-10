<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210207121750 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE pe_Config (pe_Config_id INT NOT NULL, pe_Config_payload VARCHAR(255) DEFAULT NULL, PRIMARY KEY(pe_Config_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE pe_DataMissionHashes (pe_DataMissionHashes_id BIGINT AUTO_INCREMENT NOT NULL, pe_DataMissionHashes_hash VARCHAR(150) NOT NULL, pe_DataMissionHashes_datetime DATETIME DEFAULT CURRENT_TIMESTAMP NOT NULL, pe_DataMissionHashes_instance INT NOT NULL, INDEX IDX_6D223399EDCC6074 (pe_DataMissionHashes_instance), PRIMARY KEY(pe_DataMissionHashes_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE pe_DataPlayers (pe_DataPlayers_id BIGINT AUTO_INCREMENT NOT NULL, pe_DataPlayers_ucid VARCHAR(150) NOT NULL, pe_DataPlayers_lastname VARCHAR(150) DEFAULT NULL, pe_DataPlayers_lastip VARCHAR(100) DEFAULT NULL, pe_DataPlayers_updated DATETIME DEFAULT CURRENT_TIMESTAMP NOT NULL, PRIMARY KEY(pe_DataPlayers_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE pe_DataRaw (pe_dataraw_type INT NOT NULL, pe_DataMissionHashes_instance INT NOT NULL, pe_dataraw_payload MEDIUMTEXT DEFAULT NULL, pe_dataraw_updated DATETIME on update CURRENT_TIMESTAMP, PRIMARY KEY(pe_dataraw_type, pe_DataMissionHashes_instance)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE pe_DataTypes (pe_DataTypes_id INT AUTO_INCREMENT NOT NULL, pe_DataTypes_name VARCHAR(100) NOT NULL, pe_DataTypes_update DATETIME DEFAULT CURRENT_TIMESTAMP NOT NULL, PRIMARY KEY(pe_DataTypes_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE pe_LogChat (pe_LogChat_id BIGINT AUTO_INCREMENT NOT NULL, pe_LogChat_datetime DATETIME DEFAULT CURRENT_TIMESTAMP NOT NULL, pe_LogChat_msg MEDIUMTEXT NOT NULL, pe_LogChat_all VARCHAR(10) NOT NULL, pe_LogChat_missionhash_id BIGINT DEFAULT NULL, INDEX IDX_3AC14338425634B0 (pe_LogChat_missionhash_id), PRIMARY KEY(pe_LogChat_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE pe_LogLogins (pe_LogLogins_id BIGINT AUTO_INCREMENT NOT NULL, pe_LogLogins_datetime DATETIME DEFAULT CURRENT_TIMESTAMP NOT NULL, pe_LogLogins_name VARCHAR(150) NOT NULL, pe_LogLogins_ip VARCHAR(100) NOT NULL, pe_LogLogins_instance INT NOT NULL, pe_LogLogins_playerid BIGINT DEFAULT NULL, INDEX IDX_F902E37AA120C68F (pe_LogLogins_instance), INDEX IDX_F902E37AF4738FD8 (pe_LogLogins_playerid), PRIMARY KEY(pe_LogLogins_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE pe_LogStats (pe_LogStats_id BIGINT AUTO_INCREMENT NOT NULL, pe_LogStats_datetime DATETIME DEFAULT CURRENT_TIMESTAMP NOT NULL, pe_LogStats_masterslot INT DEFAULT NULL, pe_LogStats_seat INT DEFAULT NULL, ps_kills_X INT UNSIGNED DEFAULT 0 NOT NULL, ps_pvp INT UNSIGNED DEFAULT 0 NOT NULL, ps_deaths INT UNSIGNED DEFAULT 0 NOT NULL, ps_ejections INT UNSIGNED DEFAULT 0 NOT NULL, ps_crashes INT UNSIGNED DEFAULT 0 NOT NULL, ps_teamkills INT UNSIGNED DEFAULT 0 NOT NULL, ps_kills_planes INT UNSIGNED DEFAULT 0 NOT NULL, ps_kills_helicopters INT UNSIGNED DEFAULT 0 NOT NULL, ps_kills_air_defense INT UNSIGNED DEFAULT 0 NOT NULL, ps_kills_armor INT UNSIGNED DEFAULT 0 NOT NULL, ps_kills_unarmed INT UNSIGNED DEFAULT 0 NOT NULL, ps_kills_infantry INT UNSIGNED DEFAULT 0 NOT NULL, ps_kills_ships INT UNSIGNED DEFAULT 0 NOT NULL, ps_kills_fortification INT UNSIGNED DEFAULT 0 NOT NULL, ps_kills_artillery INT UNSIGNED DEFAULT 0 NOT NULL, ps_kills_other INT UNSIGNED DEFAULT 0 NOT NULL, ps_airfield_takeoffs INT UNSIGNED DEFAULT 0 NOT NULL, ps_airfield_landings INT UNSIGNED DEFAULT 0 NOT NULL, ps_ship_takeoffs INT UNSIGNED DEFAULT 0 NOT NULL, ps_ship_landings INT UNSIGNED DEFAULT 0 NOT NULL, ps_farp_takeoffs INT UNSIGNED DEFAULT 0 NOT NULL, ps_farp_landings INT UNSIGNED DEFAULT 0 NOT NULL, ps_other_landings INT UNSIGNED DEFAULT 0 NOT NULL, ps_other_takeoffs INT UNSIGNED DEFAULT 0 NOT NULL, ps_time INT UNSIGNED DEFAULT 0 NOT NULL, pe_LogStats_mstatus enum(\'?\', \'RTB\', \'MIA\', \'KIA\'), pe_LogStats_missionhash_id BIGINT DEFAULT NULL, pe_LogStats_playerid BIGINT DEFAULT NULL, pe_LogStats_typeid INT DEFAULT NULL, INDEX IDX_4919C973A72A4E45 (pe_LogStats_missionhash_id), INDEX IDX_4919C9734F759C5C (pe_LogStats_playerid), INDEX IDX_4919C97397C8303F (pe_LogStats_typeid), PRIMARY KEY(pe_LogStats_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE pe_OnlinePlayers (id INT AUTO_INCREMENT NOT NULL, pe_OnlinePlayers_ping INT DEFAULT NULL, pe_OnlinePlayers_side INT DEFAULT NULL, pe_OnlinePlayers_ucid VARCHAR(255) DEFAULT NULL, pe_OnlinePlayers_slot VARCHAR(255) DEFAULT NULL, pe_OnlinePlayers_updated DATETIME on update CURRENT_TIMESTAMP, pe_OnlinePlayers_id BIGINT NOT NULL, pe_OnlinePlayers_instance INT NOT NULL, INDEX IDX_676CFD2FB0467609 (pe_OnlinePlayers_id), INDEX IDX_676CFD2FC31A124 (pe_OnlinePlayers_instance), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE pe_OnlineStatus (pe_OnlineStatus_instance INT NOT NULL, pe_OnlineStatus_theatre VARCHAR(255) DEFAULT NULL, pe_OnlineStatus_name VARCHAR(255) DEFAULT NULL, pe_OnlineStatus_players INT DEFAULT NULL, pe_OnlineStatus_pause VARCHAR(255) DEFAULT NULL, pe_OnlineStatus_multiplayer VARCHAR(255) DEFAULT NULL, pe_OnlineStatus_realtime VARCHAR(255) DEFAULT NULL, pe_OnlineStatus_modeltime VARCHAR(255) DEFAULT NULL, pe_OnlineStatus_perunversion_winapp VARCHAR(255) DEFAULT NULL, pe_OnlineStatus_perunversion_dcshook VARCHAR(255) DEFAULT NULL, pe_OnlineStatus_updated DATETIME on update CURRENT_TIMESTAMP, PRIMARY KEY(pe_OnlineStatus_instance)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user (id INT AUTO_INCREMENT NOT NULL, player_id BIGINT DEFAULT NULL, email VARCHAR(180) NOT NULL, roles LONGTEXT NOT NULL COMMENT \'(DC2Type:simple_array)\', password VARCHAR(255) NOT NULL, nickname VARCHAR(255) NOT NULL, UNIQUE INDEX UNIQ_8D93D649E7927C74 (email), UNIQUE INDEX UNIQ_8D93D64999E6F5DF (player_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE pe_DataMissionHashes ADD CONSTRAINT FK_6D223399EDCC6074 FOREIGN KEY (pe_DataMissionHashes_instance) REFERENCES pe_OnlineStatus (pe_OnlineStatus_instance)');
        $this->addSql('ALTER TABLE pe_LogChat ADD CONSTRAINT FK_3AC14338425634B0 FOREIGN KEY (pe_LogChat_missionhash_id) REFERENCES pe_DataMissionHashes (pe_DataMissionHashes_id)');
        $this->addSql('ALTER TABLE pe_LogLogins ADD CONSTRAINT FK_F902E37AA120C68F FOREIGN KEY (pe_LogLogins_instance) REFERENCES pe_OnlineStatus (pe_OnlineStatus_instance)');
        $this->addSql('ALTER TABLE pe_LogLogins ADD CONSTRAINT FK_F902E37AF4738FD8 FOREIGN KEY (pe_LogLogins_playerid) REFERENCES pe_DataPlayers (pe_DataPlayers_id)');
        $this->addSql('ALTER TABLE pe_LogStats ADD CONSTRAINT FK_4919C973A72A4E45 FOREIGN KEY (pe_LogStats_missionhash_id) REFERENCES pe_DataMissionHashes (pe_DataMissionHashes_id)');
        $this->addSql('ALTER TABLE pe_LogStats ADD CONSTRAINT FK_4919C9734F759C5C FOREIGN KEY (pe_LogStats_playerid) REFERENCES pe_DataPlayers (pe_DataPlayers_id)');
        $this->addSql('ALTER TABLE pe_LogStats ADD CONSTRAINT FK_4919C97397C8303F FOREIGN KEY (pe_LogStats_typeid) REFERENCES pe_DataTypes (pe_DataTypes_id)');
        $this->addSql('ALTER TABLE pe_OnlinePlayers ADD CONSTRAINT FK_676CFD2FB0467609 FOREIGN KEY (pe_OnlinePlayers_id) REFERENCES pe_DataPlayers (pe_DataPlayers_id)');
        $this->addSql('ALTER TABLE pe_OnlinePlayers ADD CONSTRAINT FK_676CFD2FC31A124 FOREIGN KEY (pe_OnlinePlayers_instance) REFERENCES pe_OnlineStatus (pe_OnlineStatus_instance)');
        $this->addSql('ALTER TABLE user ADD CONSTRAINT FK_8D93D64999E6F5DF FOREIGN KEY (player_id) REFERENCES pe_DataPlayers (pe_DataPlayers_id)');
        $this->addSql('CREATE TABLE pe_LogEvent (pe_LogEvent_id BIGINT AUTO_INCREMENT NOT NULL, pe_LogEvent_datetime DATETIME DEFAULT CURRENT_TIMESTAMP, pe_LogEvent_missionhash_id BIGINT DEFAULT NULL, pe_LogEvent_type VARCHAR(100) CHARACTER SET utf8 NOT NULL COLLATE `utf8_general_ci`, pe_LogEvent_content TEXT CHARACTER SET utf8 NOT NULL COLLATE `utf8_general_ci`, pe_LogEvent_arg1 VARCHAR(150) CHARACTER SET utf8 DEFAULT NULL COLLATE `utf8_general_ci`, pe_LogEvent_arg2 VARCHAR(150) CHARACTER SET utf8 DEFAULT NULL COLLATE `utf8_general_ci`, pe_LogEvent_argPlayers VARCHAR(150) CHARACTER SET utf8 DEFAULT NULL COLLATE `utf8_general_ci`, INDEX pe_LogEvent_datetime (pe_LogEvent_datetime), INDEX pe_LogEvent_type_2 (pe_LogEvent_type), INDEX pe_LogEvent_missionhash_id (pe_LogEvent_missionhash_id), PRIMARY KEY(pe_LogEvent_id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE pe_LogChat DROP FOREIGN KEY FK_3AC14338425634B0');
        $this->addSql('ALTER TABLE pe_LogStats DROP FOREIGN KEY FK_4919C973A72A4E45');
        $this->addSql('ALTER TABLE pe_LogLogins DROP FOREIGN KEY FK_F902E37AF4738FD8');
        $this->addSql('ALTER TABLE pe_LogStats DROP FOREIGN KEY FK_4919C9734F759C5C');
        $this->addSql('ALTER TABLE pe_OnlinePlayers DROP FOREIGN KEY FK_676CFD2FB0467609');
        $this->addSql('ALTER TABLE user DROP FOREIGN KEY FK_8D93D64999E6F5DF');
        $this->addSql('ALTER TABLE pe_LogStats DROP FOREIGN KEY FK_4919C97397C8303F');
        $this->addSql('ALTER TABLE pe_DataMissionHashes DROP FOREIGN KEY FK_6D223399EDCC6074');
        $this->addSql('ALTER TABLE pe_LogLogins DROP FOREIGN KEY FK_F902E37AA120C68F');
        $this->addSql('ALTER TABLE pe_OnlinePlayers DROP FOREIGN KEY FK_676CFD2FC31A124');
        $this->addSql('DROP TABLE pe_Config');
        $this->addSql('DROP TABLE pe_DataMissionHashes');
        $this->addSql('DROP TABLE pe_DataPlayers');
        $this->addSql('DROP TABLE pe_DataRaw');
        $this->addSql('DROP TABLE pe_DataTypes');
        $this->addSql('DROP TABLE pe_LogChat');
        $this->addSql('DROP TABLE pe_LogLogins');
        $this->addSql('DROP TABLE pe_LogStats');
        $this->addSql('DROP TABLE pe_OnlinePlayers');
        $this->addSql('DROP TABLE pe_OnlineStatus');
        $this->addSql('DROP TABLE user');
    }
}
