<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230807133338 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE system ADD creator_id_id INT NOT NULL');
        $this->addSql('ALTER TABLE system ADD CONSTRAINT FK_C94D118BF05788E9 FOREIGN KEY (creator_id_id) REFERENCES user (id)');
        $this->addSql('CREATE INDEX IDX_C94D118BF05788E9 ON system (creator_id_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE system DROP FOREIGN KEY FK_C94D118BF05788E9');
        $this->addSql('DROP INDEX IDX_C94D118BF05788E9 ON system');
        $this->addSql('ALTER TABLE system DROP creator_id_id');
    }
}
