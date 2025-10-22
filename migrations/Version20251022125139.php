<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20251022125139 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // remove FK to puzzle_answer but keep the table to migrate values
        $this->addSql('ALTER TABLE user_answer DROP CONSTRAINT fk_bf8f5118b60b7588');

        // add puzzle_id as nullable first, we'll fill it from puzzle_answer
        $this->addSql('ALTER TABLE user_answer ADD puzzle_id INT DEFAULT NULL');

        // copy puzzle_id values from puzzle_answer before dropping that table
        $this->addSql('UPDATE user_answer ua SET puzzle_id = pa.puzzle_id FROM puzzle_answer pa WHERE ua.puzzle_answer_id = pa.id');

        // enforce NOT NULL after population
        $this->addSql('ALTER TABLE user_answer ALTER COLUMN puzzle_id SET NOT NULL');

        // now it's safe to drop the old puzzle_answer objects
        $this->addSql('DROP SEQUENCE puzzle_answer_id_seq CASCADE');
        $this->addSql('ALTER TABLE puzzle_answer DROP CONSTRAINT fk_68517d73d9816812');
        $this->addSql('DROP TABLE puzzle_answer');

        // add question as nullable first to avoid NOT NULL violation
        $this->addSql('ALTER TABLE puzzle ADD question VARCHAR(255) DEFAULT NULL');
        $this->addSql("UPDATE puzzle SET question = '' WHERE question IS NULL");
        $this->addSql('ALTER TABLE puzzle ALTER COLUMN question SET NOT NULL');

        $this->addSql('ALTER TABLE puzzle ADD media VARCHAR(1000) DEFAULT NULL');

        // add type_answer as nullable, populate existing rows, then set NOT NULL to avoid violation
        $this->addSql('ALTER TABLE puzzle ADD type_answer VARCHAR(3) DEFAULT NULL');
        $this->addSql("UPDATE puzzle SET type_answer = 'TXT' WHERE type_answer IS NULL");
        $this->addSql('ALTER TABLE puzzle ALTER COLUMN type_answer SET NOT NULL');

        // add content_answer_json as nullable, populate existing rows with an empty JSON array, then set NOT NULL
        $this->addSql('ALTER TABLE puzzle ADD content_answer_json JSON DEFAULT NULL');
        $this->addSql("UPDATE puzzle SET content_answer_json = '[]' WHERE content_answer_json IS NULL");
        $this->addSql('ALTER TABLE puzzle ALTER COLUMN content_answer_json SET NOT NULL');

        $this->addSql('ALTER TABLE puzzle DROP content');
        $this->addSql('ALTER TABLE "user" ALTER password TYPE VARCHAR(255)');
        $this->addSql('DROP INDEX idx_bf8f5118b60b7588');

        // --- IMPORTANT FIX HERE ---
        // We already used puzzle_answer_id to populate puzzle_id; drop the old column instead of renaming it.
        $this->addSql('ALTER TABLE user_answer DROP COLUMN puzzle_answer_id');

        // add a new player_id column nullable. Do NOT add the FK yet (will fail if values are invalid).
        $this->addSql('ALTER TABLE user_answer ADD player_id INT DEFAULT NULL');

        // rename content and keep existing data
        $this->addSql('ALTER TABLE user_answer RENAME COLUMN content TO content_answer_json');

        // add FK only for puzzle_id (already populated)
        $this->addSql('ALTER TABLE user_answer ADD CONSTRAINT FK_BF8F5118D9816812 FOREIGN KEY (puzzle_id) REFERENCES puzzle (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE INDEX IDX_BF8F5118D9816812 ON user_answer (puzzle_id)');

        // create index on player_id to prepare for a future FK addition
        $this->addSql('CREATE INDEX IDX_BF8F511899E6F5DF ON user_answer (player_id)');

        // Note: after running this migration, manually map `user_answer.player_id` to valid `user.id` values,
        // then create a small follow-up migration to:
        //  - set player_id NOT NULL (if required by your entity)
        //  - add FOREIGN KEY (player_id) REFERENCES "user"(id)
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('CREATE SEQUENCE puzzle_answer_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE TABLE puzzle_answer (id SERIAL NOT NULL, puzzle_id INT NOT NULL, type VARCHAR(15) NOT NULL, content JSON NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX idx_68517d73d9816812 ON puzzle_answer (puzzle_id)');
        $this->addSql('ALTER TABLE puzzle_answer ADD CONSTRAINT fk_68517d73d9816812 FOREIGN KEY (puzzle_id) REFERENCES puzzle (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE "user" ALTER password TYPE VARCHAR(30)');
        $this->addSql('ALTER TABLE puzzle ADD content VARCHAR(10000) NOT NULL');
        $this->addSql('ALTER TABLE puzzle DROP question');
        $this->addSql('ALTER TABLE puzzle DROP media');
        $this->addSql('ALTER TABLE puzzle DROP type_answer');
        $this->addSql('ALTER TABLE puzzle DROP content_answer_json');
        $this->addSql('ALTER TABLE user_answer DROP CONSTRAINT FK_BF8F5118D9816812');
        $this->addSql('DROP INDEX IDX_BF8F5118D9816812');
        $this->addSql('DROP INDEX IDX_BF8F511899E6F5DF');
        // recreate old puzzle_answer_id column (cannot restore original values automatically)
        $this->addSql('ALTER TABLE user_answer ADD puzzle_answer_id INT NOT NULL');
        $this->addSql('ALTER TABLE user_answer DROP player_id');
        $this->addSql('ALTER TABLE user_answer DROP puzzle_id');
        $this->addSql('ALTER TABLE user_answer RENAME COLUMN content_answer_json TO content');
        $this->addSql('ALTER TABLE user_answer ADD CONSTRAINT fk_bf8f5118b60b7588 FOREIGN KEY (puzzle_answer_id) REFERENCES puzzle_answer (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE INDEX idx_bf8f5118b60b7588 ON user_answer (puzzle_answer_id)');
    }
}
