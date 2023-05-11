<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230510082943 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE subscription ADD user_id INT NOT NULL');
        $this->addSql('ALTER TABLE subscription ADD CONSTRAINT FK_A3C664D3A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('CREATE INDEX IDX_A3C664D3A76ED395 ON subscription (user_id)');
        $this->addSql('ALTER TABLE system_status ADD CONSTRAINT FK_6B8ED0DDF6C202BC FOREIGN KEY (maintenance_id) REFERENCES template (id)');
        $this->addSql('ALTER TABLE system_status ADD CONSTRAINT FK_6B8ED0DD59E53FB9 FOREIGN KEY (incident_id) REFERENCES template (id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_6B8ED0DDF6C202BC ON system_status (maintenance_id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_6B8ED0DD59E53FB9 ON system_status (incident_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE subscription DROP FOREIGN KEY FK_A3C664D3A76ED395');
        $this->addSql('DROP INDEX IDX_A3C664D3A76ED395 ON subscription');
        $this->addSql('ALTER TABLE subscription DROP user_id');
        $this->addSql('ALTER TABLE system_status DROP FOREIGN KEY FK_6B8ED0DDF6C202BC');
        $this->addSql('ALTER TABLE system_status DROP FOREIGN KEY FK_6B8ED0DD59E53FB9');
        $this->addSql('DROP INDEX UNIQ_6B8ED0DDF6C202BC ON system_status');
        $this->addSql('DROP INDEX UNIQ_6B8ED0DD59E53FB9 ON system_status');
    }
}
