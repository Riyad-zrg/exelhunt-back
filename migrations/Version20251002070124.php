<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20251002070124 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE address (id SERIAL NOT NULL, country VARCHAR(30) NOT NULL, city VARCHAR(50) NOT NULL, post_code VARCHAR(5) NOT NULL, street VARCHAR(100) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE TABLE code (id SERIAL NOT NULL, hunt_id INT NOT NULL, code INT NOT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, expire_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_771530982585A34B ON code (hunt_id)');
        $this->addSql('COMMENT ON COLUMN code.created_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('COMMENT ON COLUMN code.expire_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('CREATE TABLE has_started (id SERIAL NOT NULL, player_id INT NOT NULL, puzzle_id INT DEFAULT NULL, started_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_D367FE0199E6F5DF ON has_started (player_id)');
        $this->addSql('CREATE INDEX IDX_D367FE01D9816812 ON has_started (puzzle_id)');
        $this->addSql('COMMENT ON COLUMN has_started.started_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('CREATE TABLE hunt (id SERIAL NOT NULL, created_by_id INT NOT NULL, location_id INT NOT NULL, title VARCHAR(100) NOT NULL, description VARCHAR(500) NOT NULL, visibility VARCHAR(15) NOT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, updated_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL, avatar VARCHAR(255) NOT NULL, nb_players INT NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_21FA5947B03A8386 ON hunt (created_by_id)');
        $this->addSql('CREATE INDEX IDX_21FA594764D218E ON hunt (location_id)');
        $this->addSql('COMMENT ON COLUMN hunt.created_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('COMMENT ON COLUMN hunt.updated_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('CREATE TABLE membership (id SERIAL NOT NULL, member_id INT NOT NULL, team_id INT NOT NULL, role JSON NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_86FFD2857597D3FE ON membership (member_id)');
        $this->addSql('CREATE INDEX IDX_86FFD285296CD8AE ON membership (team_id)');
        $this->addSql('CREATE TABLE participation (id SERIAL NOT NULL, hunt_id INT NOT NULL, player_id INT NOT NULL, tracking VARCHAR(15) NOT NULL, global_time TIME(0) WITHOUT TIME ZONE DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_AB55E24F2585A34B ON participation (hunt_id)');
        $this->addSql('CREATE INDEX IDX_AB55E24F99E6F5DF ON participation (player_id)');
        $this->addSql('CREATE TABLE puzzle (id SERIAL NOT NULL, hunt_id INT NOT NULL, title VARCHAR(100) NOT NULL, content VARCHAR(500) NOT NULL, hint VARCHAR(500) DEFAULT NULL, time_limit TIME(0) WITHOUT TIME ZONE DEFAULT NULL, index INT NOT NULL, malus TIME(0) WITHOUT TIME ZONE DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_22A6DFDF2585A34B ON puzzle (hunt_id)');
        $this->addSql('CREATE TABLE puzzle_answer (id SERIAL NOT NULL, type VARCHAR(15) NOT NULL, content JSON NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE TABLE team (id SERIAL NOT NULL, name VARCHAR(30) NOT NULL, avatar VARCHAR(255) NOT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, PRIMARY KEY(id))');
        $this->addSql('COMMENT ON COLUMN team.created_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('CREATE TABLE "user" (id SERIAL NOT NULL, address_id INT DEFAULT NULL, nickname VARCHAR(18) NOT NULL, password VARCHAR(30) NOT NULL, roles JSON NOT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, avatar VARCHAR(255) NOT NULL, firstname VARCHAR(30) DEFAULT NULL, lastname VARCHAR(30) DEFAULT NULL, email VARCHAR(100) DEFAULT NULL, biography TEXT DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_8D93D649F5B7AF75 ON "user" (address_id)');
        $this->addSql('COMMENT ON COLUMN "user".created_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('CREATE TABLE user_answer (id SERIAL NOT NULL, content JSON NOT NULL, is_correct BOOLEAN NOT NULL, send_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, PRIMARY KEY(id))');
        $this->addSql('COMMENT ON COLUMN user_answer.send_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('CREATE TABLE messenger_messages (id BIGSERIAL NOT NULL, body TEXT NOT NULL, headers TEXT NOT NULL, queue_name VARCHAR(190) NOT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, available_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, delivered_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_75EA56E0FB7336F0 ON messenger_messages (queue_name)');
        $this->addSql('CREATE INDEX IDX_75EA56E0E3BD61CE ON messenger_messages (available_at)');
        $this->addSql('CREATE INDEX IDX_75EA56E016BA31DB ON messenger_messages (delivered_at)');
        $this->addSql('COMMENT ON COLUMN messenger_messages.created_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('COMMENT ON COLUMN messenger_messages.available_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('COMMENT ON COLUMN messenger_messages.delivered_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('CREATE OR REPLACE FUNCTION notify_messenger_messages() RETURNS TRIGGER AS $$
            BEGIN
                PERFORM pg_notify(\'messenger_messages\', NEW.queue_name::text);
                RETURN NEW;
            END;
        $$ LANGUAGE plpgsql;');
        $this->addSql('DROP TRIGGER IF EXISTS notify_trigger ON messenger_messages;');
        $this->addSql('CREATE TRIGGER notify_trigger AFTER INSERT OR UPDATE ON messenger_messages FOR EACH ROW EXECUTE PROCEDURE notify_messenger_messages();');
        $this->addSql('ALTER TABLE code ADD CONSTRAINT FK_771530982585A34B FOREIGN KEY (hunt_id) REFERENCES hunt (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE has_started ADD CONSTRAINT FK_D367FE0199E6F5DF FOREIGN KEY (player_id) REFERENCES "user" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE has_started ADD CONSTRAINT FK_D367FE01D9816812 FOREIGN KEY (puzzle_id) REFERENCES puzzle (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE hunt ADD CONSTRAINT FK_21FA5947B03A8386 FOREIGN KEY (created_by_id) REFERENCES team (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE hunt ADD CONSTRAINT FK_21FA594764D218E FOREIGN KEY (location_id) REFERENCES address (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE membership ADD CONSTRAINT FK_86FFD2857597D3FE FOREIGN KEY (member_id) REFERENCES "user" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE membership ADD CONSTRAINT FK_86FFD285296CD8AE FOREIGN KEY (team_id) REFERENCES team (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE participation ADD CONSTRAINT FK_AB55E24F2585A34B FOREIGN KEY (hunt_id) REFERENCES hunt (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE participation ADD CONSTRAINT FK_AB55E24F99E6F5DF FOREIGN KEY (player_id) REFERENCES "user" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE puzzle ADD CONSTRAINT FK_22A6DFDF2585A34B FOREIGN KEY (hunt_id) REFERENCES hunt (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE "user" ADD CONSTRAINT FK_8D93D649F5B7AF75 FOREIGN KEY (address_id) REFERENCES address (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE code DROP CONSTRAINT FK_771530982585A34B');
        $this->addSql('ALTER TABLE has_started DROP CONSTRAINT FK_D367FE0199E6F5DF');
        $this->addSql('ALTER TABLE has_started DROP CONSTRAINT FK_D367FE01D9816812');
        $this->addSql('ALTER TABLE hunt DROP CONSTRAINT FK_21FA5947B03A8386');
        $this->addSql('ALTER TABLE hunt DROP CONSTRAINT FK_21FA594764D218E');
        $this->addSql('ALTER TABLE membership DROP CONSTRAINT FK_86FFD2857597D3FE');
        $this->addSql('ALTER TABLE membership DROP CONSTRAINT FK_86FFD285296CD8AE');
        $this->addSql('ALTER TABLE participation DROP CONSTRAINT FK_AB55E24F2585A34B');
        $this->addSql('ALTER TABLE participation DROP CONSTRAINT FK_AB55E24F99E6F5DF');
        $this->addSql('ALTER TABLE puzzle DROP CONSTRAINT FK_22A6DFDF2585A34B');
        $this->addSql('ALTER TABLE "user" DROP CONSTRAINT FK_8D93D649F5B7AF75');
        $this->addSql('DROP TABLE address');
        $this->addSql('DROP TABLE code');
        $this->addSql('DROP TABLE has_started');
        $this->addSql('DROP TABLE hunt');
        $this->addSql('DROP TABLE membership');
        $this->addSql('DROP TABLE participation');
        $this->addSql('DROP TABLE puzzle');
        $this->addSql('DROP TABLE puzzle_answer');
        $this->addSql('DROP TABLE team');
        $this->addSql('DROP TABLE "user"');
        $this->addSql('DROP TABLE user_answer');
        $this->addSql('DROP TABLE messenger_messages');
    }
}
