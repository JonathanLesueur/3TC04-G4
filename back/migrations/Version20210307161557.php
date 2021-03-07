<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210307161557 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE offer CHANGE type type ENUM(\'sale\', \'purchase\', \'service\', \'search\')');
        $this->addSql('ALTER TABLE offer_comment ADD offer_id INT NOT NULL');
        $this->addSql('ALTER TABLE offer_comment ADD CONSTRAINT FK_357C946553C674EE FOREIGN KEY (offer_id) REFERENCES offer (id)');
        $this->addSql('CREATE INDEX IDX_357C946553C674EE ON offer_comment (offer_id)');
        $this->addSql('ALTER TABLE rapid_post CHANGE type type ENUM(\'initial\', \'response\')');
        $this->addSql('ALTER TABLE rapid_post_channel CHANGE type type ENUM(\'manual\', \'auto\')');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE offer CHANGE type type VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('ALTER TABLE offer_comment DROP FOREIGN KEY FK_357C946553C674EE');
        $this->addSql('DROP INDEX IDX_357C946553C674EE ON offer_comment');
        $this->addSql('ALTER TABLE offer_comment DROP offer_id');
        $this->addSql('ALTER TABLE rapid_post CHANGE type type VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('ALTER TABLE rapid_post_channel CHANGE type type VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_unicode_ci`');
    }
}
