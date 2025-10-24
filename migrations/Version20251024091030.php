<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20251024091030 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE code ALTER expire_at TYPE TIMESTAMP(0) WITHOUT TIME ZONE');
        $this->addSql('COMMENT ON COLUMN code.expire_at IS NULL');
        $this->addSql('ALTER TABLE hunt ALTER updated_at TYPE TIMESTAMP(0) WITHOUT TIME ZONE');
        $this->addSql('ALTER TABLE hunt ALTER avatar TYPE TEXT');
        $this->addSql('ALTER TABLE hunt ALTER avatar TYPE TEXT');
        $this->addSql('COMMENT ON COLUMN hunt.updated_at IS NULL');
        $this->addSql('ALTER TABLE puzzle ALTER media TYPE TEXT');
        $this->addSql('ALTER TABLE puzzle ALTER media TYPE TEXT');
        $this->addSql('ALTER TABLE team ALTER name TYPE VARCHAR(30)');
        $this->addSql('ALTER TABLE team ALTER avatar TYPE TEXT');
        $this->addSql('ALTER TABLE team ALTER avatar TYPE TEXT');
        $this->addSql('ALTER TABLE team ALTER team_global_time TYPE TIME(0) WITHOUT TIME ZONE');
        $this->addSql('ALTER TABLE team ALTER average_global_time TYPE TIME(0) WITHOUT TIME ZONE');
        $this->addSql('COMMENT ON COLUMN team.team_global_time IS NULL');
        $this->addSql('COMMENT ON COLUMN team.average_global_time IS NULL');
        $this->addSql('ALTER TABLE "user" ALTER avatar TYPE TEXT');
        $this->addSql('ALTER TABLE "user" ALTER avatar TYPE TEXT');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE puzzle ALTER media TYPE VARCHAR(1000)');
        $this->addSql('ALTER TABLE hunt ALTER updated_at TYPE TIMESTAMP(0) WITHOUT TIME ZONE');
        $this->addSql('ALTER TABLE hunt ALTER avatar TYPE VARCHAR(10000)');
        $this->addSql('COMMENT ON COLUMN hunt.updated_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('ALTER TABLE team ALTER name TYPE VARCHAR(60)');
        $this->addSql('ALTER TABLE team ALTER avatar TYPE VARCHAR(10000)');
        $this->addSql('ALTER TABLE team ALTER team_global_time TYPE TIMESTAMP(0) WITHOUT TIME ZONE');
        $this->addSql('ALTER TABLE team ALTER average_global_time TYPE TIMESTAMP(0) WITHOUT TIME ZONE');
        $this->addSql('COMMENT ON COLUMN team.team_global_time IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('COMMENT ON COLUMN team.average_global_time IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('ALTER TABLE "user" ALTER avatar TYPE VARCHAR(10000)');
        $this->addSql('ALTER TABLE code ALTER expire_at TYPE TIMESTAMP(0) WITHOUT TIME ZONE');
        $this->addSql('COMMENT ON COLUMN code.expire_at IS \'(DC2Type:datetime_immutable)\'');
    }
}
