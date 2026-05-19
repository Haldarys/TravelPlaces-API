<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20260519152304 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE location_image (id INT AUTO_INCREMENT NOT NULL, filename VARCHAR(255) NOT NULL, mime_type VARCHAR(50) NOT NULL, position INT NOT NULL, created_at DATETIME NOT NULL, location_id INT NOT NULL, INDEX IDX_B361417A64D218E (location_id), PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('ALTER TABLE location_image ADD CONSTRAINT FK_B361417A64D218E FOREIGN KEY (location_id) REFERENCES location (id)');
        $this->addSql('ALTER TABLE location CHANGE tags tags JSON NOT NULL, CHANGE external_refs external_refs JSON NOT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE location_image DROP FOREIGN KEY FK_B361417A64D218E');
        $this->addSql('DROP TABLE location_image');
        $this->addSql('ALTER TABLE location CHANGE tags tags JSON DEFAULT NULL, CHANGE external_refs external_refs JSON DEFAULT NULL');
    }
}
