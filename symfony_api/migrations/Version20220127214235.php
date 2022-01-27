<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220127214235 extends AbstractMigration
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
        $this->addSql('CREATE TABLE laboral_sector (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE laboral_sector_assignments (id INT AUTO_INCREMENT NOT NULL, user_id_id INT NOT NULL, laboral_sector_id_id INT NOT NULL, UNIQUE INDEX UNIQ_B0A5662F9D86650F (user_id_id), INDEX IDX_B0A5662FE06A9B8F (laboral_sector_id_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user (id INT AUTO_INCREMENT NOT NULL, email VARCHAR(255) NOT NULL, firstname VARCHAR(255) NOT NULL, lastname VARCHAR(255) NOT NULL, birth_date TIMESTAMP, created TIMESTAMP DEFAULT CURRENT_TIMESTAMP, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE knowledge_assignments ADD CONSTRAINT FK_C8E128359D86650F FOREIGN KEY (user_id_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE knowledge_assignments ADD CONSTRAINT FK_C8E128355E9F5FA8 FOREIGN KEY (knowledge_id_id) REFERENCES knowledge (id)');
        $this->addSql('ALTER TABLE laboral_sector_assignments ADD CONSTRAINT FK_B0A5662F9D86650F FOREIGN KEY (user_id_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE laboral_sector_assignments ADD CONSTRAINT FK_B0A5662FE06A9B8F FOREIGN KEY (laboral_sector_id_id) REFERENCES laboral_sector (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE knowledge_assignments DROP FOREIGN KEY FK_C8E128355E9F5FA8');
        $this->addSql('ALTER TABLE laboral_sector_assignments DROP FOREIGN KEY FK_B0A5662FE06A9B8F');
        $this->addSql('ALTER TABLE knowledge_assignments DROP FOREIGN KEY FK_C8E128359D86650F');
        $this->addSql('ALTER TABLE laboral_sector_assignments DROP FOREIGN KEY FK_B0A5662F9D86650F');
        $this->addSql('DROP TABLE knowledge');
        $this->addSql('DROP TABLE knowledge_assignments');
        $this->addSql('DROP TABLE laboral_sector');
        $this->addSql('DROP TABLE laboral_sector_assignments');
        $this->addSql('DROP TABLE user');
    }
}
