<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250830145943 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // Step 1: Add column as nullable
        $this->addSql('ALTER TABLE comment ADD created DATETIME DEFAULT NULL');

        // Step 2: Fill with some sensible default (e.g. now)
        $this->addSql('UPDATE comment SET created = NOW() WHERE created IS NULL');

        // Step 3: Enforce NOT NULL
        $this->addSql('ALTER TABLE comment MODIFY created DATETIME NOT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE comment DROP created');
    }
}
