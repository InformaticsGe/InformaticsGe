<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20190908061741 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE problem_submission (id INT AUTO_INCREMENT NOT NULL, user_id INT NOT NULL, problem_id INT NOT NULL, INDEX IDX_93281AB6A76ED395 (user_id), INDEX IDX_93281AB6A0DCED86 (problem_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE problem_submission_result (id INT AUTO_INCREMENT NOT NULL, submission_id INT NOT NULL, verdict VARCHAR(5) NOT NULL, time SMALLINT NOT NULL, memory SMALLINT NOT NULL, error VARCHAR(1000) DEFAULT NULL, output LONGTEXT DEFAULT NULL, INDEX IDX_55F55778E1FD4933 (submission_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE problem_submission ADD CONSTRAINT FK_93281AB6A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE problem_submission ADD CONSTRAINT FK_93281AB6A0DCED86 FOREIGN KEY (problem_id) REFERENCES problem (id)');
        $this->addSql('ALTER TABLE problem_submission_result ADD CONSTRAINT FK_55F55778E1FD4933 FOREIGN KEY (submission_id) REFERENCES problem_submission (id)');
        $this->addSql('ALTER TABLE blog CHANGE tags tags VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE material_problem CHANGE tags tags VARCHAR(255) DEFAULT NULL, CHANGE url url VARCHAR(255) DEFAULT NULL, CHANGE url_title url_title VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE user CHANGE roles roles JSON NOT NULL, CHANGE university university VARCHAR(50) DEFAULT NULL, CHANGE favorite_language favorite_language VARCHAR(50) DEFAULT NULL, CHANGE interests interests VARCHAR(255) DEFAULT NULL, CHANGE city city VARCHAR(50) DEFAULT NULL');
        $this->addSql('ALTER TABLE material_algorithm CHANGE tags tags VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE problem CHANGE tags tags VARCHAR(255) DEFAULT NULL, CHANGE source_title source_title VARCHAR(255) DEFAULT NULL, CHANGE source_url source_url VARCHAR(255) DEFAULT NULL, CHANGE sample_tests sample_tests LONGTEXT DEFAULT NULL COMMENT \'(DC2Type:array)\'');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE problem_submission_result DROP FOREIGN KEY FK_55F55778E1FD4933');
        $this->addSql('DROP TABLE problem_submission');
        $this->addSql('DROP TABLE problem_submission_result');
        $this->addSql('ALTER TABLE blog CHANGE tags tags VARCHAR(255) DEFAULT \'NULL\' COLLATE utf8mb4_unicode_ci');
        $this->addSql('ALTER TABLE material_algorithm CHANGE tags tags VARCHAR(255) DEFAULT \'NULL\' COLLATE utf8mb4_unicode_ci');
        $this->addSql('ALTER TABLE material_problem CHANGE tags tags VARCHAR(255) DEFAULT \'NULL\' COLLATE utf8mb4_unicode_ci, CHANGE url url VARCHAR(255) DEFAULT \'NULL\' COLLATE utf8mb4_unicode_ci, CHANGE url_title url_title VARCHAR(255) DEFAULT \'NULL\' COLLATE utf8mb4_unicode_ci');
        $this->addSql('ALTER TABLE problem CHANGE tags tags VARCHAR(255) DEFAULT \'NULL\' COLLATE utf8mb4_unicode_ci, CHANGE source_title source_title VARCHAR(255) DEFAULT \'NULL\' COLLATE utf8mb4_unicode_ci, CHANGE source_url source_url VARCHAR(255) DEFAULT \'NULL\' COLLATE utf8mb4_unicode_ci, CHANGE sample_tests sample_tests LONGTEXT DEFAULT \'NULL\' COLLATE utf8mb4_unicode_ci COMMENT \'(DC2Type:array)\'');
        $this->addSql('ALTER TABLE user CHANGE roles roles LONGTEXT NOT NULL COLLATE utf8mb4_bin, CHANGE university university VARCHAR(50) DEFAULT \'NULL\' COLLATE utf8mb4_unicode_ci, CHANGE favorite_language favorite_language VARCHAR(50) DEFAULT \'NULL\' COLLATE utf8mb4_unicode_ci, CHANGE interests interests VARCHAR(255) DEFAULT \'NULL\' COLLATE utf8mb4_unicode_ci, CHANGE city city VARCHAR(50) DEFAULT \'NULL\' COLLATE utf8mb4_unicode_ci');
    }
}
