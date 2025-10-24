<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20251023063056 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP SEQUENCE participation_team_id_seq CASCADE');
        $this->addSql('ALTER TABLE participation_team DROP CONSTRAINT fk_189419562585a34b');
        $this->addSql('ALTER TABLE participation_team DROP CONSTRAINT fk_189419562ee3eb38');
        $this->addSql('DROP TABLE participation_team');
        $this->addSql('ALTER TABLE code ALTER code TYPE VARCHAR(6)');
        $this->addSql('ALTER TABLE team ADD hunt_id INT NOT NULL');
        $this->addSql('ALTER TABLE team ADD CONSTRAINT FK_C4E0A61F2585A34B FOREIGN KEY (hunt_id) REFERENCES hunt (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE INDEX IDX_C4E0A61F2585A34B ON team (hunt_id)');
        $this->addSql('ALTER TABLE user_answer ALTER player_id SET NOT NULL');
        $this->addSql('ALTER TABLE user_answer ADD CONSTRAINT FK_BF8F511899E6F5DF FOREIGN KEY (player_id) REFERENCES "user" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('CREATE SEQUENCE participation_team_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE TABLE participation_team (id SERIAL NOT NULL, team_player_id INT NOT NULL, hunt_id INT NOT NULL, team_global_time TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL, average_team_time TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX idx_189419562585a34b ON participation_team (hunt_id)');
        $this->addSql('CREATE INDEX idx_189419562ee3eb38 ON participation_team (team_player_id)');
        $this->addSql('COMMENT ON COLUMN participation_team.team_global_time IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('COMMENT ON COLUMN participation_team.average_team_time IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('ALTER TABLE participation_team ADD CONSTRAINT fk_189419562585a34b FOREIGN KEY (hunt_id) REFERENCES hunt (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE participation_team ADD CONSTRAINT fk_189419562ee3eb38 FOREIGN KEY (team_player_id) REFERENCES team (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE code ALTER code TYPE INT');
        $this->addSql('ALTER TABLE code ALTER code TYPE INT');
        $this->addSql('ALTER TABLE team DROP CONSTRAINT FK_C4E0A61F2585A34B');
        $this->addSql('DROP INDEX IDX_C4E0A61F2585A34B');
        $this->addSql('ALTER TABLE team DROP hunt_id');
        $this->addSql('ALTER TABLE user_answer DROP CONSTRAINT FK_BF8F511899E6F5DF');
        $this->addSql('ALTER TABLE user_answer ALTER player_id DROP NOT NULL');
    }
}
