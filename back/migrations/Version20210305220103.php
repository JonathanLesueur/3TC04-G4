<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210305220103 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE blog_post_comment ADD post_id INT NOT NULL');
        $this->addSql('ALTER TABLE blog_post_comment ADD CONSTRAINT FK_F3400AD84B89032C FOREIGN KEY (post_id) REFERENCES blog_post (id)');
        $this->addSql('CREATE INDEX IDX_F3400AD84B89032C ON blog_post_comment (post_id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE blog_post_comment DROP FOREIGN KEY FK_F3400AD84B89032C');
        $this->addSql('DROP INDEX IDX_F3400AD84B89032C ON blog_post_comment');
        $this->addSql('ALTER TABLE blog_post_comment DROP post_id');
    }
}
