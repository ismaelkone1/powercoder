<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
<<<<<<<< HEAD:webApp/migrations/Version20250213163419.php
final class Version20250213163419 extends AbstractMigration
========
final class Version20250213162946 extends AbstractMigration
>>>>>>>> 436b4ffe2770d23889ff78294a02cfbb1d1dda69:webApp/migrations/Version20250213162946.php
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs

    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
    }
}
