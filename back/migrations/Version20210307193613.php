<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210307193613 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE like_blog_post (like_id INT NOT NULL, blog_post_id INT NOT NULL, INDEX IDX_92FA6C7F859BFA32 (like_id), INDEX IDX_92FA6C7FA77FBEAF (blog_post_id), PRIMARY KEY(like_id, blog_post_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE like_blog_post_comment (like_id INT NOT NULL, blog_post_comment_id INT NOT NULL, INDEX IDX_C69558B3859BFA32 (like_id), INDEX IDX_C69558B369E80851 (blog_post_comment_id), PRIMARY KEY(like_id, blog_post_comment_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE like_rapid_post (like_id INT NOT NULL, rapid_post_id INT NOT NULL, INDEX IDX_192C36AD859BFA32 (like_id), INDEX IDX_192C36ADEE973FF3 (rapid_post_id), PRIMARY KEY(like_id, rapid_post_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE like_blog_post ADD CONSTRAINT FK_92FA6C7F859BFA32 FOREIGN KEY (like_id) REFERENCES `like` (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE like_blog_post ADD CONSTRAINT FK_92FA6C7FA77FBEAF FOREIGN KEY (blog_post_id) REFERENCES blog_post (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE like_blog_post_comment ADD CONSTRAINT FK_C69558B3859BFA32 FOREIGN KEY (like_id) REFERENCES `like` (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE like_blog_post_comment ADD CONSTRAINT FK_C69558B369E80851 FOREIGN KEY (blog_post_comment_id) REFERENCES blog_post_comment (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE like_rapid_post ADD CONSTRAINT FK_192C36AD859BFA32 FOREIGN KEY (like_id) REFERENCES `like` (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE like_rapid_post ADD CONSTRAINT FK_192C36ADEE973FF3 FOREIGN KEY (rapid_post_id) REFERENCES rapid_post (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE offer CHANGE type type ENUM(\'sale\', \'purchase\', \'service\', \'search\')');
        $this->addSql('ALTER TABLE rapid_post CHANGE type type ENUM(\'initial\', \'response\')');
        $this->addSql('ALTER TABLE rapid_post_channel CHANGE type type ENUM(\'manual\', \'auto\')');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE like_blog_post');
        $this->addSql('DROP TABLE like_blog_post_comment');
        $this->addSql('DROP TABLE like_rapid_post');
        $this->addSql('ALTER TABLE offer CHANGE type type VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('ALTER TABLE rapid_post CHANGE type type VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('ALTER TABLE rapid_post_channel CHANGE type type VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_unicode_ci`');
    }
}
