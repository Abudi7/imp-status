<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230509131850 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE system_status ADD maintenance_id INT NOT NULL');
        $this->addSql('ALTER TABLE system_status ADD CONSTRAINT FK_6B8ED0DDF6C202BC FOREIGN KEY (maintenance_id) REFERENCES tamplate (id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_6B8ED0DDF6C202BC ON system_status (maintenance_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE system_status DROP FOREIGN KEY FK_6B8ED0DDF6C202BC');
        $this->addSql('DROP INDEX UNIQ_6B8ED0DDF6C202BC ON system_status');
        $this->addSql('ALTER TABLE system_status DROP maintenance_id');
    }
}
