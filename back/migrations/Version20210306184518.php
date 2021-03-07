<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210306184518 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE rapid_post (id INT AUTO_INCREMENT NOT NULL, author_id INT NOT NULL, title VARCHAR(255) NOT NULL, content LONGTEXT NOT NULL, created_at DATETIME NOT NULL, INDEX IDX_BAB89655F675F31B (author_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE rapid_post_rapid_post_channel (rapid_post_id INT NOT NULL, rapid_post_channel_id INT NOT NULL, INDEX IDX_990B76B3EE973FF3 (rapid_post_id), INDEX IDX_990B76B3606870F0 (rapid_post_channel_id), PRIMARY KEY(rapid_post_id, rapid_post_channel_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE rapid_post_channel (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, description LONGTEXT DEFAULT NULL, logo VARCHAR(255) DEFAULT NULL, created_at DATETIME NOT NULL, updated_at DATETIME NOT NULL, type TINYINT(1) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE rapid_post ADD CONSTRAINT FK_BAB89655F675F31B FOREIGN KEY (author_id) REFERENCES `user` (id)');
        $this->addSql('ALTER TABLE rapid_post_rapid_post_channel ADD CONSTRAINT FK_990B76B3EE973FF3 FOREIGN KEY (rapid_post_id) REFERENCES rapid_post (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE rapid_post_rapid_post_channel ADD CONSTRAINT FK_990B76B3606870F0 FOREIGN KEY (rapid_post_channel_id) REFERENCES rapid_post_channel (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE rapid_post_rapid_post_channel DROP FOREIGN KEY FK_990B76B3EE973FF3');
        $this->addSql('ALTER TABLE rapid_post_rapid_post_channel DROP FOREIGN KEY FK_990B76B3606870F0');
        $this->addSql('DROP TABLE rapid_post');
        $this->addSql('DROP TABLE rapid_post_rapid_post_channel');
        $this->addSql('DROP TABLE rapid_post_channel');
    }
}
