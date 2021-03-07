<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210307125524 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE offer CHANGE type type ENUM(\'sale\', \'purchase\', \'service\', \'search\')');
        $this->addSql('ALTER TABLE rapid_post ADD responses_id INT DEFAULT NULL, ADD type ENUM(\'initial\', \'response\')');
        $this->addSql('ALTER TABLE rapid_post ADD CONSTRAINT FK_BAB8965591560F9D FOREIGN KEY (responses_id) REFERENCES rapid_post (id)');
        $this->addSql('CREATE INDEX IDX_BAB8965591560F9D ON rapid_post (responses_id)');
        $this->addSql('ALTER TABLE rapid_post_channel CHANGE type type ENUM(\'manual\', \'auto\')');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE offer CHANGE type type VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('ALTER TABLE rapid_post DROP FOREIGN KEY FK_BAB8965591560F9D');
        $this->addSql('DROP INDEX IDX_BAB8965591560F9D ON rapid_post');
        $this->addSql('ALTER TABLE rapid_post DROP responses_id, DROP type');
        $this->addSql('ALTER TABLE rapid_post_channel CHANGE type type VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_unicode_ci`');
    }
}
