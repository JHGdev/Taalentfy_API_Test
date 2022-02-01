<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220201202853 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE knowledge (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE knowledge_assignments (id INT AUTO_INCREMENT NOT NULL, user_id_id INT NOT NULL, knowledge_id_id INT DEFAULT NULL, INDEX IDX_C8E128359D86650F (user_id_id), INDEX IDX_C8E128355E9F5FA8 (knowledge_id_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE knowledge_offer_assignments (id INT AUTO_INCREMENT NOT NULL, offer_id_id INT NOT NULL, knowledge_id_id INT NOT NULL, INDEX IDX_D5ED8C58FC69E3BE (offer_id_id), INDEX IDX_D5ED8C585E9F5FA8 (knowledge_id_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE laboral_sector (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE laboral_sector_assignments (id INT AUTO_INCREMENT NOT NULL, user_id_id INT NOT NULL, laboral_sector_id_id INT NOT NULL, UNIQUE INDEX UNIQ_B0A5662F9D86650F (user_id_id), INDEX IDX_B0A5662FE06A9B8F (laboral_sector_id_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE laboral_sector_offer_assignments (id INT AUTO_INCREMENT NOT NULL, offer_id_id INT NOT NULL, laboral_sector_id_id INT NOT NULL, UNIQUE INDEX UNIQ_DEF7BD0FFC69E3BE (offer_id_id), INDEX IDX_DEF7BD0FE06A9B8F (laboral_sector_id_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE offer (id INT AUTO_INCREMENT NOT NULL, title VARCHAR(255) NOT NULL, description LONGTEXT NOT NULL, status SMALLINT NOT NULL, incorporation_date BIGINT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE offer_criteria_test_a (id INT AUTO_INCREMENT NOT NULL, offer_id_id INT NOT NULL, minimun_percent SMALLINT NOT NULL, UNIQUE INDEX UNIQ_368BBD63FC69E3BE (offer_id_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE offer_criteria_test_b (id INT AUTO_INCREMENT NOT NULL, offer_id_id INT NOT NULL, desired_percent_a SMALLINT NOT NULL, desired_percent_b SMALLINT NOT NULL, desired_percent_c SMALLINT NOT NULL, UNIQUE INDEX UNIQ_AF82ECD9FC69E3BE (offer_id_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user (id INT AUTO_INCREMENT NOT NULL, email VARCHAR(255) NOT NULL, firstname VARCHAR(255) NOT NULL, lastname VARCHAR(255) NOT NULL, birth_date BIGINT, created BIGINT, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user_answers_test_a (id INT AUTO_INCREMENT NOT NULL, user_id_id INT NOT NULL, total_percent SMALLINT NOT NULL, UNIQUE INDEX UNIQ_296849509D86650F (user_id_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user_answers_test_b (id INT AUTO_INCREMENT NOT NULL, user_id_id INT NOT NULL, percent_answer_a SMALLINT NOT NULL, percent_answer_b SMALLINT NOT NULL, percent_answer_c SMALLINT NOT NULL, UNIQUE INDEX UNIQ_B06118EA9D86650F (user_id_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE knowledge_assignments ADD CONSTRAINT FK_C8E128359D86650F FOREIGN KEY (user_id_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE knowledge_assignments ADD CONSTRAINT FK_C8E128355E9F5FA8 FOREIGN KEY (knowledge_id_id) REFERENCES knowledge (id)');
        $this->addSql('ALTER TABLE knowledge_offer_assignments ADD CONSTRAINT FK_D5ED8C58FC69E3BE FOREIGN KEY (offer_id_id) REFERENCES offer (id)');
        $this->addSql('ALTER TABLE knowledge_offer_assignments ADD CONSTRAINT FK_D5ED8C585E9F5FA8 FOREIGN KEY (knowledge_id_id) REFERENCES knowledge (id)');
        $this->addSql('ALTER TABLE laboral_sector_assignments ADD CONSTRAINT FK_B0A5662F9D86650F FOREIGN KEY (user_id_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE laboral_sector_assignments ADD CONSTRAINT FK_B0A5662FE06A9B8F FOREIGN KEY (laboral_sector_id_id) REFERENCES laboral_sector (id)');
        $this->addSql('ALTER TABLE laboral_sector_offer_assignments ADD CONSTRAINT FK_DEF7BD0FFC69E3BE FOREIGN KEY (offer_id_id) REFERENCES offer (id)');
        $this->addSql('ALTER TABLE laboral_sector_offer_assignments ADD CONSTRAINT FK_DEF7BD0FE06A9B8F FOREIGN KEY (laboral_sector_id_id) REFERENCES laboral_sector (id)');
        $this->addSql('ALTER TABLE offer_criteria_test_a ADD CONSTRAINT FK_368BBD63FC69E3BE FOREIGN KEY (offer_id_id) REFERENCES offer (id)');
        $this->addSql('ALTER TABLE offer_criteria_test_b ADD CONSTRAINT FK_AF82ECD9FC69E3BE FOREIGN KEY (offer_id_id) REFERENCES offer (id)');
        $this->addSql('ALTER TABLE user_answers_test_a ADD CONSTRAINT FK_296849509D86650F FOREIGN KEY (user_id_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE user_answers_test_b ADD CONSTRAINT FK_B06118EA9D86650F FOREIGN KEY (user_id_id) REFERENCES user (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE knowledge_assignments DROP FOREIGN KEY FK_C8E128355E9F5FA8');
        $this->addSql('ALTER TABLE knowledge_offer_assignments DROP FOREIGN KEY FK_D5ED8C585E9F5FA8');
        $this->addSql('ALTER TABLE laboral_sector_assignments DROP FOREIGN KEY FK_B0A5662FE06A9B8F');
        $this->addSql('ALTER TABLE laboral_sector_offer_assignments DROP FOREIGN KEY FK_DEF7BD0FE06A9B8F');
        $this->addSql('ALTER TABLE knowledge_offer_assignments DROP FOREIGN KEY FK_D5ED8C58FC69E3BE');
        $this->addSql('ALTER TABLE laboral_sector_offer_assignments DROP FOREIGN KEY FK_DEF7BD0FFC69E3BE');
        $this->addSql('ALTER TABLE offer_criteria_test_a DROP FOREIGN KEY FK_368BBD63FC69E3BE');
        $this->addSql('ALTER TABLE offer_criteria_test_b DROP FOREIGN KEY FK_AF82ECD9FC69E3BE');
        $this->addSql('ALTER TABLE knowledge_assignments DROP FOREIGN KEY FK_C8E128359D86650F');
        $this->addSql('ALTER TABLE laboral_sector_assignments DROP FOREIGN KEY FK_B0A5662F9D86650F');
        $this->addSql('ALTER TABLE user_answers_test_a DROP FOREIGN KEY FK_296849509D86650F');
        $this->addSql('ALTER TABLE user_answers_test_b DROP FOREIGN KEY FK_B06118EA9D86650F');
        $this->addSql('DROP TABLE knowledge');
        $this->addSql('DROP TABLE knowledge_assignments');
        $this->addSql('DROP TABLE knowledge_offer_assignments');
        $this->addSql('DROP TABLE laboral_sector');
        $this->addSql('DROP TABLE laboral_sector_assignments');
        $this->addSql('DROP TABLE laboral_sector_offer_assignments');
        $this->addSql('DROP TABLE offer');
        $this->addSql('DROP TABLE offer_criteria_test_a');
        $this->addSql('DROP TABLE offer_criteria_test_b');
        $this->addSql('DROP TABLE user');
        $this->addSql('DROP TABLE user_answers_test_a');
        $this->addSql('DROP TABLE user_answers_test_b');
    }
}
