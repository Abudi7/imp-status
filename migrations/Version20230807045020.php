<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230807045020 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE evnets (id INT AUTO_INCREMENT NOT NULL, type VARCHAR(255) NOT NULL, startevent DATETIME NOT NULL, endevent DATETIME DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE system_status ADD CONSTRAINT FK_6B8ED0DDD0952FA5 FOREIGN KEY (system_id) REFERENCES system (id)');
        $this->addSql('ALTER TABLE system_status ADD CONSTRAINT FK_6B8ED0DD6BF700BD FOREIGN KEY (status_id) REFERENCES status (id)');
        $this->addSql('CREATE INDEX IDX_6B8ED0DDD0952FA5 ON system_status (system_id)');
        $this->addSql('ALTER TABLE template CHANGE template template LONGTEXT DEFAULT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE evnets');
        $this->addSql('ALTER TABLE system_status DROP FOREIGN KEY FK_6B8ED0DDD0952FA5');
        $this->addSql('ALTER TABLE system_status DROP FOREIGN KEY FK_6B8ED0DD6BF700BD');
        $this->addSql('DROP INDEX IDX_6B8ED0DDD0952FA5 ON system_status');
        $this->addSql('ALTER TABLE template CHANGE template template TEXT NOT NULL');
    }
}
