<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230509135457 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE tamplate');
        $this->addSql('ALTER TABLE system_status ADD CONSTRAINT FK_6B8ED0DDF6C202BC FOREIGN KEY (maintenance_id) REFERENCES template (id)');
        $this->addSql('ALTER TABLE system_status ADD CONSTRAINT FK_6B8ED0DD59E53FB9 FOREIGN KEY (incident_id) REFERENCES template (id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_6B8ED0DDF6C202BC ON system_status (maintenance_id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_6B8ED0DD59E53FB9 ON system_status (incident_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE tamplate (id INT AUTO_INCREMENT NOT NULL, maintenance LONGTEXT CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_unicode_ci`, incident LONGTEXT CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_unicode_ci`, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('ALTER TABLE system_status DROP FOREIGN KEY FK_6B8ED0DDF6C202BC');
        $this->addSql('ALTER TABLE system_status DROP FOREIGN KEY FK_6B8ED0DD59E53FB9');
        $this->addSql('DROP INDEX UNIQ_6B8ED0DDF6C202BC ON system_status');
        $this->addSql('DROP INDEX UNIQ_6B8ED0DD59E53FB9 ON system_status');
    }
}
