<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20190908062418 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE blog CHANGE tags tags VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE problem_submission ADD code VARCHAR(2048) NOT NULL, ADD language VARCHAR(20) NOT NULL, ADD verdict VARCHAR(5) NOT NULL, ADD created_at DATETIME NOT NULL');
        $this->addSql('ALTER TABLE material_problem CHANGE tags tags VARCHAR(255) DEFAULT NULL, CHANGE url url VARCHAR(255) DEFAULT NULL, CHANGE url_title url_title VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE problem_submission_result CHANGE error error VARCHAR(1000) DEFAULT NULL');
        $this->addSql('ALTER TABLE user CHANGE roles roles JSON NOT NULL, CHANGE university university VARCHAR(50) DEFAULT NULL, CHANGE favorite_language favorite_language VARCHAR(50) DEFAULT NULL, CHANGE interests interests VARCHAR(255) DEFAULT NULL, CHANGE city city VARCHAR(50) DEFAULT NULL');
        $this->addSql('ALTER TABLE material_algorithm CHANGE tags tags VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE problem CHANGE tags tags VARCHAR(255) DEFAULT NULL, CHANGE source_title source_title VARCHAR(255) DEFAULT NULL, CHANGE source_url source_url VARCHAR(255) DEFAULT NULL, CHANGE sample_tests sample_tests LONGTEXT DEFAULT NULL COMMENT \'(DC2Type:array)\'');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE blog CHANGE tags tags VARCHAR(255) DEFAULT \'NULL\' COLLATE utf8mb4_unicode_ci');
        $this->addSql('ALTER TABLE material_algorithm CHANGE tags tags VARCHAR(255) DEFAULT \'NULL\' COLLATE utf8mb4_unicode_ci');
        $this->addSql('ALTER TABLE material_problem CHANGE tags tags VARCHAR(255) DEFAULT \'NULL\' COLLATE utf8mb4_unicode_ci, CHANGE url url VARCHAR(255) DEFAULT \'NULL\' COLLATE utf8mb4_unicode_ci, CHANGE url_title url_title VARCHAR(255) DEFAULT \'NULL\' COLLATE utf8mb4_unicode_ci');
        $this->addSql('ALTER TABLE problem CHANGE tags tags VARCHAR(255) DEFAULT \'NULL\' COLLATE utf8mb4_unicode_ci, CHANGE source_title source_title VARCHAR(255) DEFAULT \'NULL\' COLLATE utf8mb4_unicode_ci, CHANGE source_url source_url VARCHAR(255) DEFAULT \'NULL\' COLLATE utf8mb4_unicode_ci, CHANGE sample_tests sample_tests LONGTEXT DEFAULT \'NULL\' COLLATE utf8mb4_unicode_ci COMMENT \'(DC2Type:array)\'');
        $this->addSql('ALTER TABLE problem_submission DROP code, DROP language, DROP verdict, DROP created_at');
        $this->addSql('ALTER TABLE problem_submission_result CHANGE error error VARCHAR(1000) DEFAULT \'NULL\' COLLATE utf8mb4_unicode_ci');
        $this->addSql('ALTER TABLE user CHANGE roles roles LONGTEXT NOT NULL COLLATE utf8mb4_bin, CHANGE university university VARCHAR(50) DEFAULT \'NULL\' COLLATE utf8mb4_unicode_ci, CHANGE favorite_language favorite_language VARCHAR(50) DEFAULT \'NULL\' COLLATE utf8mb4_unicode_ci, CHANGE interests interests VARCHAR(255) DEFAULT \'NULL\' COLLATE utf8mb4_unicode_ci, CHANGE city city VARCHAR(50) DEFAULT \'NULL\' COLLATE utf8mb4_unicode_ci');
    }
}
