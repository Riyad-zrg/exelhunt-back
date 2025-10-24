<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20251021145301 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE participation ADD team_player_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE participation ADD CONSTRAINT FK_AB55E24F2EE3EB38 FOREIGN KEY (team_player_id) REFERENCES team (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE INDEX IDX_AB55E24F2EE3EB38 ON participation (team_player_id)');
        $this->addSql('ALTER TABLE team ADD team_global_time TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL');
        $this->addSql('ALTER TABLE team ADD average_global_time TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL');
        $this->addSql('ALTER TABLE team ALTER avatar SET NOT NULL');
        $this->addSql('ALTER TABLE team ALTER avatar TYPE VARCHAR(10000)');
        $this->addSql('COMMENT ON COLUMN team.team_global_time IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('COMMENT ON COLUMN team.average_global_time IS \'(DC2Type:datetime_immutable)\'');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE team DROP team_global_time');
        $this->addSql('ALTER TABLE team DROP average_global_time');
        $this->addSql('ALTER TABLE team ALTER avatar DROP NOT NULL');
        $this->addSql('ALTER TABLE team ALTER avatar TYPE VARCHAR(1000)');
        $this->addSql('ALTER TABLE participation DROP CONSTRAINT FK_AB55E24F2EE3EB38');
        $this->addSql('DROP INDEX IDX_AB55E24F2EE3EB38');
        $this->addSql('ALTER TABLE participation DROP team_player_id');
    }
}
