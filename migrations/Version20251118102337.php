<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20251118102337 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP SEQUENCE puzzle_answer_id_seq CASCADE');
        $this->addSql('ALTER TABLE puzzle_answer DROP CONSTRAINT fk_68517d73d9816812');
        $this->addSql('DROP TABLE puzzle_answer');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('CREATE SEQUENCE puzzle_answer_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE TABLE puzzle_answer (id SERIAL NOT NULL, puzzle_id INT NOT NULL, type VARCHAR(15) NOT NULL, content JSON NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX idx_68517d73d9816812 ON puzzle_answer (puzzle_id)');
        $this->addSql('ALTER TABLE puzzle_answer ADD CONSTRAINT fk_68517d73d9816812 FOREIGN KEY (puzzle_id) REFERENCES puzzle (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
    }
}
