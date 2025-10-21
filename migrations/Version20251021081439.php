<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20251021081439 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE participation_team (id SERIAL NOT NULL, team_player_id INT NOT NULL, hunt_id INT NOT NULL, team_global_time TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL, average_team_time TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_189419562EE3EB38 ON participation_team (team_player_id)');
        $this->addSql('CREATE INDEX IDX_189419562585A34B ON participation_team (hunt_id)');
        $this->addSql('COMMENT ON COLUMN participation_team.team_global_time IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('COMMENT ON COLUMN participation_team.average_team_time IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('ALTER TABLE participation_team ADD CONSTRAINT FK_189419562EE3EB38 FOREIGN KEY (team_player_id) REFERENCES team (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE participation_team ADD CONSTRAINT FK_189419562585A34B FOREIGN KEY (hunt_id) REFERENCES hunt (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE hunt ADD is_team_playable BOOLEAN DEFAULT false NOT NULL');
        $this->addSql('ALTER TABLE hunt ADD team_player_max INT DEFAULT NULL');
        $this->addSql('ALTER TABLE puzzle ALTER content TYPE VARCHAR(1000)');
        $this->addSql('ALTER TABLE team ADD code_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE team ADD type VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE team ADD nb_players INT DEFAULT NULL');
        $this->addSql('ALTER TABLE team ADD is_public BOOLEAN DEFAULT NULL');
        $this->addSql('ALTER TABLE team ALTER avatar DROP NOT NULL');
        $this->addSql('ALTER TABLE team ALTER avatar TYPE VARCHAR(1000)');
        $this->addSql('ALTER TABLE team ALTER created_at DROP NOT NULL');
        $this->addSql('ALTER TABLE team ADD CONSTRAINT FK_C4E0A61F27DAFE17 FOREIGN KEY (code_id) REFERENCES code (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE INDEX IDX_C4E0A61F27DAFE17 ON team (code_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE participation_team DROP CONSTRAINT FK_189419562EE3EB38');
        $this->addSql('ALTER TABLE participation_team DROP CONSTRAINT FK_189419562585A34B');
        $this->addSql('DROP TABLE participation_team');
        $this->addSql('ALTER TABLE hunt DROP is_team_playable');
        $this->addSql('ALTER TABLE hunt DROP team_player_max');
        $this->addSql('ALTER TABLE team DROP CONSTRAINT FK_C4E0A61F27DAFE17');
        $this->addSql('DROP INDEX IDX_C4E0A61F27DAFE17');
        $this->addSql('ALTER TABLE team DROP code_id');
        $this->addSql('ALTER TABLE team DROP type');
        $this->addSql('ALTER TABLE team DROP nb_players');
        $this->addSql('ALTER TABLE team DROP is_public');
        $this->addSql('ALTER TABLE team ALTER avatar SET NOT NULL');
        $this->addSql('ALTER TABLE team ALTER avatar TYPE VARCHAR(5000)');
        $this->addSql('ALTER TABLE team ALTER created_at SET NOT NULL');
        $this->addSql('ALTER TABLE puzzle ALTER content TYPE VARCHAR(500)');
    }
}
