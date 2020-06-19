<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200619124700 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('DROP SEQUENCE refresh_token_id_seq CASCADE');
        $this->addSql('ALTER TABLE refresh_token ALTER id DROP DEFAULT');
        $this->addSql('ALTER TABLE refresh_token ALTER COLUMN id SET DATA TYPE UUID USING (uuid_generate_v4())');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('CREATE SEQUENCE refresh_token_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('ALTER TABLE refresh_token ALTER id DROP DEFAULT');
        $this->addSql('ALTER TABLE refresh_token ALTER COLUMN id SET DATA TYPE INT USING (nextval(\'refresh_token_id_seq\'))');
    }
}
