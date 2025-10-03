<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20251003120712 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE user_answer ADD puzzle_answer_id INT NOT NULL');
        $this->addSql('ALTER TABLE user_answer ADD CONSTRAINT FK_BF8F5118B60B7588 FOREIGN KEY (puzzle_answer_id) REFERENCES puzzle_answer (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE INDEX IDX_BF8F5118B60B7588 ON user_answer (puzzle_answer_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE user_answer DROP CONSTRAINT FK_BF8F5118B60B7588');
        $this->addSql('DROP INDEX IDX_BF8F5118B60B7588');
        $this->addSql('ALTER TABLE user_answer DROP puzzle_answer_id');
    }
}
