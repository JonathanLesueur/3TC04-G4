<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210312203344 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE blog_post ADD association_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE blog_post ADD CONSTRAINT FK_BA5AE01DEFB9C8A5 FOREIGN KEY (association_id) REFERENCES association (id)');
        $this->addSql('CREATE INDEX IDX_BA5AE01DEFB9C8A5 ON blog_post (association_id)');
        $this->addSql('ALTER TABLE offer CHANGE type type ENUM(\'sale\', \'purchase\', \'service\', \'search\')');
        $this->addSql('ALTER TABLE rapid_post CHANGE type type ENUM(\'initial\', \'response\')');
        $this->addSql('ALTER TABLE rapid_post_channel CHANGE type type ENUM(\'manual\', \'auto\')');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE blog_post DROP FOREIGN KEY FK_BA5AE01DEFB9C8A5');
        $this->addSql('DROP INDEX IDX_BA5AE01DEFB9C8A5 ON blog_post');
        $this->addSql('ALTER TABLE blog_post DROP association_id');
        $this->addSql('ALTER TABLE offer CHANGE type type VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('ALTER TABLE rapid_post CHANGE type type VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('ALTER TABLE rapid_post_channel CHANGE type type VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_unicode_ci`');
    }
}
