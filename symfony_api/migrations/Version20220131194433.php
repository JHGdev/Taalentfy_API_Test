<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220131194433 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE offer_criteria_test_a (id INT AUTO_INCREMENT NOT NULL, offer_id_id INT NOT NULL, minimun_percent SMALLINT NOT NULL, UNIQUE INDEX UNIQ_368BBD63FC69E3BE (offer_id_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE offer_criteria_test_b (id INT AUTO_INCREMENT NOT NULL, offer_id_id INT NOT NULL, desired_percent_a SMALLINT NOT NULL, desired_percent_b SMALLINT NOT NULL, desired_percent_c SMALLINT NOT NULL, UNIQUE INDEX UNIQ_AF82ECD9FC69E3BE (offer_id_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user_answers_test_a (id INT AUTO_INCREMENT NOT NULL, user_id_id INT NOT NULL, total_percent SMALLINT NOT NULL, UNIQUE INDEX UNIQ_296849509D86650F (user_id_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user_answers_test_b (id INT AUTO_INCREMENT NOT NULL, user_id_id INT NOT NULL, percent_answer_a SMALLINT NOT NULL, percent_answer_b SMALLINT NOT NULL, percent_answer_c SMALLINT NOT NULL, UNIQUE INDEX UNIQ_B06118EA9D86650F (user_id_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE offer_criteria_test_a ADD CONSTRAINT FK_368BBD63FC69E3BE FOREIGN KEY (offer_id_id) REFERENCES offer (id)');
        $this->addSql('ALTER TABLE offer_criteria_test_b ADD CONSTRAINT FK_AF82ECD9FC69E3BE FOREIGN KEY (offer_id_id) REFERENCES offer (id)');
        $this->addSql('ALTER TABLE user_answers_test_a ADD CONSTRAINT FK_296849509D86650F FOREIGN KEY (user_id_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE user_answers_test_b ADD CONSTRAINT FK_B06118EA9D86650F FOREIGN KEY (user_id_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE user CHANGE birth_date birth_date BIGINT, CHANGE created created BIGINT');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE offer_criteria_test_a');
        $this->addSql('DROP TABLE offer_criteria_test_b');
        $this->addSql('DROP TABLE user_answers_test_a');
        $this->addSql('DROP TABLE user_answers_test_b');
        $this->addSql('ALTER TABLE user CHANGE birth_date birth_date BIGINT DEFAULT NULL, CHANGE created created BIGINT DEFAULT NULL');
    }
}
