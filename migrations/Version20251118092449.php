<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20251118092449 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE puzzle_answer (id SERIAL NOT NULL, puzzle_id INT NOT NULL, type VARCHAR(15) NOT NULL, content JSON NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_68517D73D9816812 ON puzzle_answer (puzzle_id)');
        $this->addSql('CREATE TABLE team_creator (id INT NOT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, PRIMARY KEY(id))');
        $this->addSql('COMMENT ON COLUMN team_creator.created_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('CREATE TABLE team_player (id INT NOT NULL, hunt_id INT DEFAULT NULL, nb_players INT NOT NULL, is_public BOOLEAN NOT NULL, team_global_time TIME(0) WITHOUT TIME ZONE DEFAULT NULL, average_global_time TIME(0) WITHOUT TIME ZONE DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_EE023DBC2585A34B ON team_player (hunt_id)');
        $this->addSql('ALTER TABLE puzzle_answer ADD CONSTRAINT FK_68517D73D9816812 FOREIGN KEY (puzzle_id) REFERENCES puzzle (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE team_creator ADD CONSTRAINT FK_437F9B54BF396750 FOREIGN KEY (id) REFERENCES team (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE team_player ADD CONSTRAINT FK_EE023DBC2585A34B FOREIGN KEY (hunt_id) REFERENCES hunt (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE team_player ADD CONSTRAINT FK_EE023DBCBF396750 FOREIGN KEY (id) REFERENCES team (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE code DROP CONSTRAINT FK_771530982EE3EB38');
        $this->addSql('DROP INDEX uniq_771530982585a34b');
        $this->addSql('ALTER TABLE code ALTER code TYPE INT USING code::integer');
        $this->addSql('ALTER TABLE code ALTER created_at TYPE TIMESTAMP(0) WITHOUT TIME ZONE');
        $this->addSql('COMMENT ON COLUMN code.created_at IS NULL');
        $this->addSql('ALTER TABLE code ADD CONSTRAINT FK_771530982EE3EB38 FOREIGN KEY (team_player_id) REFERENCES team_player (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE INDEX IDX_771530982585A34B ON code (hunt_id)');
        $this->addSql('ALTER TABLE hunt DROP CONSTRAINT FK_21FA5947B03A8386');
        $this->addSql('ALTER TABLE hunt ADD CONSTRAINT FK_21FA5947B03A8386 FOREIGN KEY (created_by_id) REFERENCES team_creator (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE participation DROP CONSTRAINT fk_ab55e24f2ee3eb38');
        $this->addSql('DROP INDEX idx_ab55e24f2ee3eb38');
        $this->addSql('ALTER TABLE participation DROP team_player_id');
        $this->addSql('ALTER TABLE puzzle DROP media');
        $this->addSql('ALTER TABLE puzzle RENAME COLUMN question TO content');
        $this->addSql('ALTER TABLE team DROP CONSTRAINT fk_c4e0a61f2585a34b');
        $this->addSql('DROP INDEX idx_c4e0a61f2585a34b');
        $this->addSql('ALTER TABLE team DROP hunt_id');
        $this->addSql('ALTER TABLE team DROP created_at');
        $this->addSql('ALTER TABLE team DROP nb_players');
        $this->addSql('ALTER TABLE team DROP is_public');
        $this->addSql('ALTER TABLE team DROP team_global_time');
        $this->addSql('ALTER TABLE team DROP average_global_time');
        $this->addSql('ALTER TABLE team ALTER name TYPE VARCHAR(60)');
        $this->addSql('ALTER TABLE team ALTER avatar TYPE VARCHAR(5000)');
        $this->addSql('ALTER TABLE team RENAME COLUMN type TO discriminator');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE hunt DROP CONSTRAINT FK_21FA5947B03A8386');
        $this->addSql('ALTER TABLE code DROP CONSTRAINT FK_771530982EE3EB38');
        $this->addSql('ALTER TABLE puzzle_answer DROP CONSTRAINT FK_68517D73D9816812');
        $this->addSql('ALTER TABLE team_creator DROP CONSTRAINT FK_437F9B54BF396750');
        $this->addSql('ALTER TABLE team_player DROP CONSTRAINT FK_EE023DBC2585A34B');
        $this->addSql('ALTER TABLE team_player DROP CONSTRAINT FK_EE023DBCBF396750');
        $this->addSql('DROP TABLE puzzle_answer');
        $this->addSql('DROP TABLE team_creator');
        $this->addSql('DROP TABLE team_player');
        $this->addSql('ALTER TABLE participation ADD team_player_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE participation ADD CONSTRAINT fk_ab55e24f2ee3eb38 FOREIGN KEY (team_player_id) REFERENCES team (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE INDEX idx_ab55e24f2ee3eb38 ON participation (team_player_id)');
        $this->addSql('ALTER TABLE puzzle ADD media TEXT DEFAULT NULL');
        $this->addSql('ALTER TABLE puzzle RENAME COLUMN content TO question');
        $this->addSql('ALTER TABLE hunt DROP CONSTRAINT fk_21fa5947b03a8386');
        $this->addSql('ALTER TABLE hunt ADD CONSTRAINT fk_21fa5947b03a8386 FOREIGN KEY (created_by_id) REFERENCES team (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE team ADD hunt_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE team ADD created_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL');
        $this->addSql('ALTER TABLE team ADD nb_players INT DEFAULT NULL');
        $this->addSql('ALTER TABLE team ADD is_public BOOLEAN DEFAULT NULL');
        $this->addSql('ALTER TABLE team ADD team_global_time TIME(0) WITHOUT TIME ZONE DEFAULT NULL');
        $this->addSql('ALTER TABLE team ADD average_global_time TIME(0) WITHOUT TIME ZONE DEFAULT NULL');
        $this->addSql('ALTER TABLE team ALTER name TYPE VARCHAR(30)');
        $this->addSql('ALTER TABLE team ALTER avatar TYPE TEXT');
        $this->addSql('ALTER TABLE team ALTER avatar TYPE TEXT');
        $this->addSql('ALTER TABLE team RENAME COLUMN discriminator TO type');
        $this->addSql('COMMENT ON COLUMN team.created_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('ALTER TABLE team ADD CONSTRAINT fk_c4e0a61f2585a34b FOREIGN KEY (hunt_id) REFERENCES hunt (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE INDEX idx_c4e0a61f2585a34b ON team (hunt_id)');
        $this->addSql('ALTER TABLE code DROP CONSTRAINT fk_771530982ee3eb38');
        $this->addSql('DROP INDEX IDX_771530982585A34B');
        $this->addSql('ALTER TABLE code ALTER code TYPE VARCHAR(6)');
        $this->addSql('ALTER TABLE code ALTER created_at TYPE TIMESTAMP(0) WITHOUT TIME ZONE');
        $this->addSql('COMMENT ON COLUMN code.created_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('ALTER TABLE code ADD CONSTRAINT fk_771530982ee3eb38 FOREIGN KEY (team_player_id) REFERENCES team (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE UNIQUE INDEX uniq_771530982585a34b ON code (hunt_id)');
    }
}
