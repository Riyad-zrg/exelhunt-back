<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20251118093555 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP INDEX idx_771530982585a34b');
        $this->addSql('ALTER TABLE code ALTER hunt_id SET NOT NULL');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_771530982585A34B ON code (hunt_id)');
        $this->addSql('ALTER TABLE participation ADD team_player_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE participation ADD CONSTRAINT FK_AB55E24F2EE3EB38 FOREIGN KEY (team_player_id) REFERENCES team_player (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE INDEX IDX_AB55E24F2EE3EB38 ON participation (team_player_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('DROP INDEX UNIQ_771530982585A34B');
        $this->addSql('ALTER TABLE code ALTER hunt_id DROP NOT NULL');
        $this->addSql('CREATE INDEX idx_771530982585a34b ON code (hunt_id)');
        $this->addSql('ALTER TABLE participation DROP CONSTRAINT FK_AB55E24F2EE3EB38');
        $this->addSql('DROP INDEX IDX_AB55E24F2EE3EB38');
        $this->addSql('ALTER TABLE participation DROP team_player_id');
    }
}
