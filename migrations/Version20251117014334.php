<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20251117014334 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP SEQUENCE has_started_id_seq CASCADE');
        $this->addSql('CREATE TABLE start (id SERIAL NOT NULL, player_id INT NOT NULL, puzzle_id INT DEFAULT NULL, started_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_9F79558F99E6F5DF ON start (player_id)');
        $this->addSql('CREATE INDEX IDX_9F79558FD9816812 ON start (puzzle_id)');
        $this->addSql('COMMENT ON COLUMN start.started_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('ALTER TABLE start ADD CONSTRAINT FK_9F79558F99E6F5DF FOREIGN KEY (player_id) REFERENCES "user" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE start ADD CONSTRAINT FK_9F79558FD9816812 FOREIGN KEY (puzzle_id) REFERENCES puzzle (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE has_started DROP CONSTRAINT fk_d367fe0199e6f5df');
        $this->addSql('ALTER TABLE has_started DROP CONSTRAINT fk_d367fe01d9816812');
        $this->addSql('DROP TABLE has_started');
        $this->addSql('ALTER TABLE puzzle RENAME COLUMN content_answer_json TO answer_content');
        $this->addSql('ALTER TABLE team DROP CONSTRAINT fk_c4e0a61f27dafe17');
        $this->addSql('DROP INDEX idx_c4e0a61f27dafe17');
        $this->addSql('ALTER TABLE team DROP code_id');
        $this->addSql('ALTER TABLE user_answer RENAME COLUMN content_answer_json TO answer_content');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('CREATE SEQUENCE has_started_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE TABLE has_started (id SERIAL NOT NULL, player_id INT NOT NULL, puzzle_id INT DEFAULT NULL, started_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX idx_d367fe0199e6f5df ON has_started (player_id)');
        $this->addSql('CREATE INDEX idx_d367fe01d9816812 ON has_started (puzzle_id)');
        $this->addSql('COMMENT ON COLUMN has_started.started_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('ALTER TABLE has_started ADD CONSTRAINT fk_d367fe0199e6f5df FOREIGN KEY (player_id) REFERENCES "user" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE has_started ADD CONSTRAINT fk_d367fe01d9816812 FOREIGN KEY (puzzle_id) REFERENCES puzzle (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE start DROP CONSTRAINT FK_9F79558F99E6F5DF');
        $this->addSql('ALTER TABLE start DROP CONSTRAINT FK_9F79558FD9816812');
        $this->addSql('DROP TABLE start');
        $this->addSql('ALTER TABLE user_answer RENAME COLUMN answer_content TO content_answer_json');
        $this->addSql('ALTER TABLE puzzle RENAME COLUMN answer_content TO content_answer_json');
        $this->addSql('ALTER TABLE team ADD code_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE team ADD CONSTRAINT fk_c4e0a61f27dafe17 FOREIGN KEY (code_id) REFERENCES code (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE INDEX idx_c4e0a61f27dafe17 ON team (code_id)');
    }
}
