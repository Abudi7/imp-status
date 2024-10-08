<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230809043334 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE events ADD creator_id INT NOT NULL, ADD type VARCHAR(255) NOT NULL, ADD start DATETIME NOT NULL, ADD end DATETIME NOT NULL, ADD template LONGTEXT NOT NULL, ADD email LONGTEXT NOT NULL');
        $this->addSql('ALTER TABLE events ADD CONSTRAINT FK_5387574A61220EA6 FOREIGN KEY (creator_id) REFERENCES user (id)');
        $this->addSql('CREATE INDEX IDX_5387574A61220EA6 ON events (creator_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE events DROP FOREIGN KEY FK_5387574A61220EA6');
        $this->addSql('DROP INDEX IDX_5387574A61220EA6 ON events');
        $this->addSql('ALTER TABLE events DROP creator_id, DROP type, DROP start, DROP end, DROP template, DROP email');
    }
}
