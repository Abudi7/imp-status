<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230508112526 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE user (id INT AUTO_INCREMENT NOT NULL, email VARCHAR(180) NOT NULL, roles LONGTEXT NOT NULL COMMENT \'(DC2Type:json)\', password VARCHAR(255) NOT NULL, UNIQUE INDEX UNIQ_8D93D649E7927C74 (email), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE system_status ADD CONSTRAINT FK_6B8ED0DDD0952FA5 FOREIGN KEY (system_id) REFERENCES system (id)');
        $this->addSql('ALTER TABLE system_status ADD CONSTRAINT FK_6B8ED0DD6BF700BD FOREIGN KEY (status_id) REFERENCES status (id)');
        $this->addSql('CREATE INDEX IDX_6B8ED0DDD0952FA5 ON system_status (system_id)');
        $this->addSql('CREATE INDEX IDX_6B8ED0DD6BF700BD ON system_status (status_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE user');
        $this->addSql('ALTER TABLE system_status DROP FOREIGN KEY FK_6B8ED0DDD0952FA5');
        $this->addSql('ALTER TABLE system_status DROP FOREIGN KEY FK_6B8ED0DD6BF700BD');
        $this->addSql('DROP INDEX IDX_6B8ED0DDD0952FA5 ON system_status');
        $this->addSql('DROP INDEX IDX_6B8ED0DD6BF700BD ON system_status');
    }
}
