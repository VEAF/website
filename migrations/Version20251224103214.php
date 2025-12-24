<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20251224103214 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE dcsbot_sync_state (server_id VARCHAR(50) NOT NULL, last_sync_at DATETIME NOT NULL, records_imported INT DEFAULT 0 NOT NULL, PRIMARY KEY(server_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE module RENAME INDEX name_idx TO UNIQ_C2426285E237E06');
        $this->addSql('ALTER TABLE module RENAME INDEX code_idx TO UNIQ_C24262877153098');
        $this->addSql('ALTER TABLE module_role RENAME INDEX name_idx TO UNIQ_ED55CF665E237E06');
        $this->addSql('ALTER TABLE module_role RENAME INDEX code_idx TO UNIQ_ED55CF6677153098');
        $this->addSql('ALTER TABLE module_system RENAME INDEX code_idx TO UNIQ_634E51A477153098');
        $this->addSql('ALTER TABLE module_system RENAME INDEX name_idx TO UNIQ_634E51A45E237E06');
        $this->addSql('ALTER TABLE variant RENAME INDEX code_idx TO UNIQ_F143BFAD77153098');
        $this->addSql('ALTER TABLE variant RENAME INDEX name_idx TO UNIQ_F143BFAD5E237E06');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE dcsbot_sync_state');
        $this->addSql('ALTER TABLE module RENAME INDEX uniq_c24262877153098 TO code_idx');
        $this->addSql('ALTER TABLE module RENAME INDEX uniq_c2426285e237e06 TO name_idx');
        $this->addSql('ALTER TABLE module_role RENAME INDEX uniq_ed55cf665e237e06 TO name_idx');
        $this->addSql('ALTER TABLE module_role RENAME INDEX uniq_ed55cf6677153098 TO code_idx');
        $this->addSql('ALTER TABLE module_system RENAME INDEX uniq_634e51a477153098 TO code_idx');
        $this->addSql('ALTER TABLE module_system RENAME INDEX uniq_634e51a45e237e06 TO name_idx');
        $this->addSql('ALTER TABLE variant RENAME INDEX uniq_f143bfad5e237e06 TO name_idx');
        $this->addSql('ALTER TABLE variant RENAME INDEX uniq_f143bfad77153098 TO code_idx');
    }
}
