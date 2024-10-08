<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230808034102 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE system ADD creator_id INT NOT NULL');
        $this->addSql('ALTER TABLE system ADD CONSTRAINT FK_C94D118B61220EA6 FOREIGN KEY (creator_id) REFERENCES user (id)');
        $this->addSql('CREATE INDEX IDX_C94D118B61220EA6 ON system (creator_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE system DROP FOREIGN KEY FK_C94D118B61220EA6');
        $this->addSql('DROP INDEX IDX_C94D118B61220EA6 ON system');
        $this->addSql('ALTER TABLE system DROP creator_id');
    }
}
