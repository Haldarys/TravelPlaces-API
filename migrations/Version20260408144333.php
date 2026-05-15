<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20260408144333 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE location
            ADD address VARCHAR(255) DEFAULT NULL,
            ADD city VARCHAR(100) DEFAULT NULL,
            ADD country_code VARCHAR(2) DEFAULT NULL,
            ADD tags JSON DEFAULT NULL,
            ADD external_refs JSON DEFAULT NULL,
            ADD created_at DATETIME NOT NULL,
            ADD updated_at DATETIME DEFAULT NULL'
        );
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE location DROP address, DROP city, DROP country_code, DROP tags, DROP external_refs, DROP created_at, DROP updated_at');
    }
}
