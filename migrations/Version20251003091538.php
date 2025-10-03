<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20251003091538 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE puzzle_answer ADD puzzle_id INT NOT NULL');
        $this->addSql('ALTER TABLE puzzle_answer ADD CONSTRAINT FK_68517D73D9816812 FOREIGN KEY (puzzle_id) REFERENCES puzzle (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE INDEX IDX_68517D73D9816812 ON puzzle_answer (puzzle_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE puzzle_answer DROP CONSTRAINT FK_68517D73D9816812');
        $this->addSql('DROP INDEX IDX_68517D73D9816812');
        $this->addSql('ALTER TABLE puzzle_answer DROP puzzle_id');
    }
}
