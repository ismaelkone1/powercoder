<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250213134250 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE besoin (id SERIAL NOT NULL, client_id_id INT NOT NULL, libelle VARCHAR(255) NOT NULL, date DATE NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_8118E811DC2902E0 ON besoin (client_id_id)');
        $this->addSql('CREATE TABLE competence (id SERIAL NOT NULL, type VARCHAR(2) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE TABLE competence_besoin (competence_id INT NOT NULL, besoin_id INT NOT NULL, PRIMARY KEY(competence_id, besoin_id))');
        $this->addSql('CREATE INDEX IDX_C8FB8D0D15761DAB ON competence_besoin (competence_id)');
        $this->addSql('CREATE INDEX IDX_C8FB8D0DFE6EED44 ON competence_besoin (besoin_id)');
        $this->addSql('CREATE TABLE salarie (id SERIAL NOT NULL, nom VARCHAR(255) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE TABLE salarie_besoin (salarie_id INT NOT NULL, besoin_id INT NOT NULL, PRIMARY KEY(salarie_id, besoin_id))');
        $this->addSql('CREATE INDEX IDX_57E2989A5859934A ON salarie_besoin (salarie_id)');
        $this->addSql('CREATE INDEX IDX_57E2989AFE6EED44 ON salarie_besoin (besoin_id)');
        $this->addSql('CREATE TABLE salarie_competence (id SERIAL NOT NULL, salarie_id INT NOT NULL, competence_id INT DEFAULT NULL, interet INT NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_7B9E4FC35859934A ON salarie_competence (salarie_id)');
        $this->addSql('CREATE INDEX IDX_7B9E4FC315761DAB ON salarie_competence (competence_id)');
        $this->addSql('CREATE TABLE "user" (id SERIAL NOT NULL, email VARCHAR(180) NOT NULL, roles JSON NOT NULL, password VARCHAR(255) NOT NULL, nom VARCHAR(255) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_IDENTIFIER_EMAIL ON "user" (email)');
        $this->addSql('CREATE TABLE messenger_messages (id BIGSERIAL NOT NULL, body TEXT NOT NULL, headers TEXT NOT NULL, queue_name VARCHAR(190) NOT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, available_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, delivered_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_75EA56E0FB7336F0 ON messenger_messages (queue_name)');
        $this->addSql('CREATE INDEX IDX_75EA56E0E3BD61CE ON messenger_messages (available_at)');
        $this->addSql('CREATE INDEX IDX_75EA56E016BA31DB ON messenger_messages (delivered_at)');
        $this->addSql('COMMENT ON COLUMN messenger_messages.created_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('COMMENT ON COLUMN messenger_messages.available_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('COMMENT ON COLUMN messenger_messages.delivered_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('CREATE OR REPLACE FUNCTION notify_messenger_messages() RETURNS TRIGGER AS $$
            BEGIN
                PERFORM pg_notify(\'messenger_messages\', NEW.queue_name::text);
                RETURN NEW;
            END;
        $$ LANGUAGE plpgsql;');
        $this->addSql('DROP TRIGGER IF EXISTS notify_trigger ON messenger_messages;');
        $this->addSql('CREATE TRIGGER notify_trigger AFTER INSERT OR UPDATE ON messenger_messages FOR EACH ROW EXECUTE PROCEDURE notify_messenger_messages();');
        $this->addSql('ALTER TABLE besoin ADD CONSTRAINT FK_8118E811DC2902E0 FOREIGN KEY (client_id_id) REFERENCES "user" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE competence_besoin ADD CONSTRAINT FK_C8FB8D0D15761DAB FOREIGN KEY (competence_id) REFERENCES competence (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE competence_besoin ADD CONSTRAINT FK_C8FB8D0DFE6EED44 FOREIGN KEY (besoin_id) REFERENCES besoin (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE salarie_besoin ADD CONSTRAINT FK_57E2989A5859934A FOREIGN KEY (salarie_id) REFERENCES salarie (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE salarie_besoin ADD CONSTRAINT FK_57E2989AFE6EED44 FOREIGN KEY (besoin_id) REFERENCES besoin (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE salarie_competence ADD CONSTRAINT FK_7B9E4FC35859934A FOREIGN KEY (salarie_id) REFERENCES salarie (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE salarie_competence ADD CONSTRAINT FK_7B9E4FC315761DAB FOREIGN KEY (competence_id) REFERENCES competence (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE besoin DROP CONSTRAINT FK_8118E811DC2902E0');
        $this->addSql('ALTER TABLE competence_besoin DROP CONSTRAINT FK_C8FB8D0D15761DAB');
        $this->addSql('ALTER TABLE competence_besoin DROP CONSTRAINT FK_C8FB8D0DFE6EED44');
        $this->addSql('ALTER TABLE salarie_besoin DROP CONSTRAINT FK_57E2989A5859934A');
        $this->addSql('ALTER TABLE salarie_besoin DROP CONSTRAINT FK_57E2989AFE6EED44');
        $this->addSql('ALTER TABLE salarie_competence DROP CONSTRAINT FK_7B9E4FC35859934A');
        $this->addSql('ALTER TABLE salarie_competence DROP CONSTRAINT FK_7B9E4FC315761DAB');
        $this->addSql('DROP TABLE besoin');
        $this->addSql('DROP TABLE competence');
        $this->addSql('DROP TABLE competence_besoin');
        $this->addSql('DROP TABLE salarie');
        $this->addSql('DROP TABLE salarie_besoin');
        $this->addSql('DROP TABLE salarie_competence');
        $this->addSql('DROP TABLE "user"');
        $this->addSql('DROP TABLE messenger_messages');
    }
}
