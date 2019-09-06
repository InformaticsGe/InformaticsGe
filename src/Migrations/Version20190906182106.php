<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20190906182106 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE problem (id INT AUTO_INCREMENT NOT NULL, title VARCHAR(255) NOT NULL, tags VARCHAR(255) DEFAULT NULL, text LONGTEXT NOT NULL, notes LONGTEXT DEFAULT NULL, time_limit SMALLINT NOT NULL, memory_limit SMALLINT NOT NULL, input_type VARCHAR(30) NOT NULL, output_type VARCHAR(30) NOT NULL, source_title VARCHAR(255) DEFAULT NULL, source_url VARCHAR(255) DEFAULT NULL, visible TINYINT(1) NOT NULL, sample_tests LONGTEXT DEFAULT NULL COMMENT \'(DC2Type:simple_array)\', PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE problem_test (id INT AUTO_INCREMENT NOT NULL, problem_id INT NOT NULL, input LONGTEXT DEFAULT NULL, output LONGTEXT DEFAULT NULL, INDEX IDX_270694EA0DCED86 (problem_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE problem_test ADD CONSTRAINT FK_270694EA0DCED86 FOREIGN KEY (problem_id) REFERENCES problem (id)');
        $this->addSql('ALTER TABLE blog CHANGE tags tags VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE material_problem CHANGE tags tags VARCHAR(255) DEFAULT NULL, CHANGE url url VARCHAR(255) DEFAULT NULL, CHANGE url_title url_title VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE user CHANGE roles roles JSON NOT NULL, CHANGE university university VARCHAR(50) DEFAULT NULL, CHANGE favorite_language favorite_language VARCHAR(50) DEFAULT NULL, CHANGE interests interests VARCHAR(255) DEFAULT NULL, CHANGE city city VARCHAR(50) DEFAULT NULL');
        $this->addSql('ALTER TABLE material_algorithm CHANGE tags tags VARCHAR(255) DEFAULT NULL');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE problem_test DROP FOREIGN KEY FK_270694EA0DCED86');
        $this->addSql('DROP TABLE problem');
        $this->addSql('DROP TABLE problem_test');
        $this->addSql('ALTER TABLE blog CHANGE tags tags VARCHAR(255) DEFAULT \'NULL\' COLLATE utf8mb4_unicode_ci');
        $this->addSql('ALTER TABLE material_algorithm CHANGE tags tags VARCHAR(255) DEFAULT \'NULL\' COLLATE utf8mb4_unicode_ci');
        $this->addSql('ALTER TABLE material_problem CHANGE tags tags VARCHAR(255) DEFAULT \'NULL\' COLLATE utf8mb4_unicode_ci, CHANGE url url VARCHAR(255) DEFAULT \'NULL\' COLLATE utf8mb4_unicode_ci, CHANGE url_title url_title VARCHAR(255) DEFAULT \'NULL\' COLLATE utf8mb4_unicode_ci');
        $this->addSql('ALTER TABLE user CHANGE roles roles LONGTEXT NOT NULL COLLATE utf8mb4_bin, CHANGE university university VARCHAR(50) DEFAULT \'NULL\' COLLATE utf8mb4_unicode_ci, CHANGE favorite_language favorite_language VARCHAR(50) DEFAULT \'NULL\' COLLATE utf8mb4_unicode_ci, CHANGE interests interests VARCHAR(255) DEFAULT \'NULL\' COLLATE utf8mb4_unicode_ci, CHANGE city city VARCHAR(50) DEFAULT \'NULL\' COLLATE utf8mb4_unicode_ci');
    }
}
