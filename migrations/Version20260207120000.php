<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20260207120000 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'ATO editor refactoring: convert flight.mission from string to integer, add departure/return bases';
    }

    public function up(Schema $schema): void
    {
        // Add new columns
        $this->addSql('ALTER TABLE flight ADD departure_base VARCHAR(64) DEFAULT NULL');
        $this->addSql('ALTER TABLE flight ADD return_base VARCHAR(64) DEFAULT NULL');

        // Add a temporary column for the integer mission
        $this->addSql('ALTER TABLE flight ADD mission_int INT DEFAULT NULL');

        // Convert known text values to integers
        $this->addSql("UPDATE flight SET mission_int = 1 WHERE UPPER(mission) LIKE '%CAP%' AND UPPER(mission) NOT LIKE '%ESCORT%'");
        $this->addSql("UPDATE flight SET mission_int = 2 WHERE UPPER(mission) LIKE '%CAS%' OR UPPER(mission) LIKE '%STRIKE%'");
        $this->addSql("UPDATE flight SET mission_int = 3 WHERE UPPER(mission) LIKE '%SEAD%'");
        $this->addSql("UPDATE flight SET mission_int = 4 WHERE UPPER(mission) LIKE '%ESCORT%'");
        $this->addSql("UPDATE flight SET mission_int = 5 WHERE UPPER(mission) LIKE '%TRANSPORT%'");
        $this->addSql("UPDATE flight SET mission_int = 6 WHERE UPPER(mission) LIKE '%RECO%'");
        $this->addSql("UPDATE flight SET mission_int = 7 WHERE UPPER(mission) LIKE '%CSAR%'");
        $this->addSql("UPDATE flight SET mission_int = 8 WHERE UPPER(mission) LIKE '%TANKER%' OR UPPER(mission) LIKE '%RAVIT%'");
        $this->addSql("UPDATE flight SET mission_int = 9 WHERE UPPER(mission) LIKE '%AWACS%'");
        $this->addSql("UPDATE flight SET mission_int = 10 WHERE UPPER(mission) LIKE '%FAC%' OR UPPER(mission) LIKE '%JTAC%'");
        // Remaining unmapped values default to 0 (undefined)
        $this->addSql('UPDATE flight SET mission_int = 0 WHERE mission_int IS NULL');

        // Drop old column, rename new one
        $this->addSql('ALTER TABLE flight DROP COLUMN mission');
        $this->addSql('ALTER TABLE flight CHANGE mission_int mission INT DEFAULT NULL');
    }

    public function down(Schema $schema): void
    {
        // Convert integer mission back to string
        $this->addSql('ALTER TABLE flight ADD mission_str VARCHAR(255) DEFAULT NULL');
        $this->addSql("UPDATE flight SET mission_str = 'CAP' WHERE mission = 1");
        $this->addSql("UPDATE flight SET mission_str = 'CAS / Strike' WHERE mission = 2");
        $this->addSql("UPDATE flight SET mission_str = 'SEAD' WHERE mission = 3");
        $this->addSql("UPDATE flight SET mission_str = 'Escorte' WHERE mission = 4");
        $this->addSql("UPDATE flight SET mission_str = 'Transport' WHERE mission = 5");
        $this->addSql("UPDATE flight SET mission_str = 'Reconnaissance' WHERE mission = 6");
        $this->addSql("UPDATE flight SET mission_str = 'CSAR' WHERE mission = 7");
        $this->addSql("UPDATE flight SET mission_str = 'Ravitailleur' WHERE mission = 8");
        $this->addSql("UPDATE flight SET mission_str = 'AWACS' WHERE mission = 9");
        $this->addSql("UPDATE flight SET mission_str = 'FAC / JTAC' WHERE mission = 10");
        $this->addSql('ALTER TABLE flight DROP COLUMN mission');
        $this->addSql('ALTER TABLE flight CHANGE mission_str mission VARCHAR(255) DEFAULT NULL');

        // Remove new columns
        $this->addSql('ALTER TABLE flight DROP departure_base');
        $this->addSql('ALTER TABLE flight DROP return_base');
    }
}
