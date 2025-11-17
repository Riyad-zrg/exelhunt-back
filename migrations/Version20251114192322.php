<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20251114192322 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE code ADD team_player_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE code ADD CONSTRAINT FK_771530982EE3EB38 FOREIGN KEY (team_player_id) REFERENCES team (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_771530982EE3EB38 ON code (team_player_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE code DROP CONSTRAINT FK_771530982EE3EB38');
        $this->addSql('DROP INDEX UNIQ_771530982EE3EB38');
        $this->addSql('ALTER TABLE code DROP team_player_id');
    }
}
