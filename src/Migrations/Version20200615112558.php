<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200615112558 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('CREATE TABLE auth_code (id UUID NOT NULL, parent_user_id UUID NOT NULL, created_at TIMESTAMP(0) WITH TIME ZONE NOT NULL, invalidate_at TIMESTAMP(0) WITH TIME ZONE NOT NULL, code VARCHAR(255) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_5933D02CD526A7D3 ON auth_code (parent_user_id)');
        $this->addSql('CREATE UNIQUE INDEX unique_user_code ON auth_code (parent_user_id, code)');
        $this->addSql('COMMENT ON COLUMN auth_code.created_at IS \'(DC2Type:datetimetz_immutable)\'');
        $this->addSql('COMMENT ON COLUMN auth_code.invalidate_at IS \'(DC2Type:datetimetz_immutable)\'');
        $this->addSql('ALTER TABLE auth_code ADD CONSTRAINT FK_5933D02CD526A7D3 FOREIGN KEY (parent_user_id) REFERENCES "user" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('DROP TABLE auth_code');
    }
}
