<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250213141159 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE besoin DROP CONSTRAINT fk_8118e811dc2902e0');
        $this->addSql('DROP INDEX idx_8118e811dc2902e0');
        $this->addSql('ALTER TABLE besoin RENAME COLUMN client_id_id TO client_id');
        $this->addSql('ALTER TABLE besoin ADD CONSTRAINT FK_8118E81119EB6921 FOREIGN KEY (client_id) REFERENCES "user" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE INDEX IDX_8118E81119EB6921 ON besoin (client_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE besoin DROP CONSTRAINT FK_8118E81119EB6921');
        $this->addSql('DROP INDEX IDX_8118E81119EB6921');
        $this->addSql('ALTER TABLE besoin RENAME COLUMN client_id TO client_id_id');
        $this->addSql('ALTER TABLE besoin ADD CONSTRAINT fk_8118e811dc2902e0 FOREIGN KEY (client_id_id) REFERENCES "user" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE INDEX idx_8118e811dc2902e0 ON besoin (client_id_id)');
    }
}
