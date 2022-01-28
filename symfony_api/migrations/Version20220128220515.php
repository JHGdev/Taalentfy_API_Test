<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220128220515 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE knowledge_offer_assignments (id INT AUTO_INCREMENT NOT NULL, offer_id_id INT NOT NULL, knowledge_id_id INT NOT NULL, INDEX IDX_D5ED8C58FC69E3BE (offer_id_id), INDEX IDX_D5ED8C585E9F5FA8 (knowledge_id_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE laboral_sector_offer_assignments (id INT AUTO_INCREMENT NOT NULL, offer_id_id INT NOT NULL, laboral_sector_id_id INT NOT NULL, UNIQUE INDEX UNIQ_DEF7BD0FFC69E3BE (offer_id_id), INDEX IDX_DEF7BD0FE06A9B8F (laboral_sector_id_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE offer (id INT AUTO_INCREMENT NOT NULL, title VARCHAR(255) NOT NULL, description LONGTEXT NOT NULL, status SMALLINT NOT NULL, incorporation_date BIGINT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE knowledge_offer_assignments ADD CONSTRAINT FK_D5ED8C58FC69E3BE FOREIGN KEY (offer_id_id) REFERENCES offer (id)');
        $this->addSql('ALTER TABLE knowledge_offer_assignments ADD CONSTRAINT FK_D5ED8C585E9F5FA8 FOREIGN KEY (knowledge_id_id) REFERENCES knowledge (id)');
        $this->addSql('ALTER TABLE laboral_sector_offer_assignments ADD CONSTRAINT FK_DEF7BD0FFC69E3BE FOREIGN KEY (offer_id_id) REFERENCES offer (id)');
        $this->addSql('ALTER TABLE laboral_sector_offer_assignments ADD CONSTRAINT FK_DEF7BD0FE06A9B8F FOREIGN KEY (laboral_sector_id_id) REFERENCES laboral_sector (id)');
        $this->addSql('ALTER TABLE laboral_sector_assignments DROP FOREIGN KEY FK_B0A5662FE06A9B8F');
        $this->addSql('DROP INDEX IDX_B0A5662FE06A9B8F ON laboral_sector_assignments');
        $this->addSql('ALTER TABLE laboral_sector_assignments DROP laboral_sector_id_id');
        $this->addSql('ALTER TABLE user CHANGE birth_date birth_date BIGINT, CHANGE created created BIGINT');
        $this->addSql('ALTER TABLE laboral_sector_assignments ADD laboral_sector_id_id INT NOT NULL;');
        $this->addSql('ALTER TABLE laboral_sector_assignments ADD CONSTRAINT FK_B0A5662FE06A9B8F FOREIGN KEY (laboral_sector_id_id) REFERENCES laboral_sector (id);');
        $this->addSql('CREATE INDEX IDX_B0A5662FE06A9B8F ON laboral_sector_assignments (laboral_sector_id_id);');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE knowledge_offer_assignments DROP FOREIGN KEY FK_D5ED8C58FC69E3BE');
        $this->addSql('ALTER TABLE laboral_sector_offer_assignments DROP FOREIGN KEY FK_DEF7BD0FFC69E3BE');
        $this->addSql('DROP TABLE knowledge_offer_assignments');
        $this->addSql('DROP TABLE laboral_sector_offer_assignments');
        $this->addSql('DROP TABLE offer');
        $this->addSql('ALTER TABLE laboral_sector_assignments ADD laboral_sector_id_id INT NOT NULL');
        $this->addSql('ALTER TABLE laboral_sector_assignments ADD CONSTRAINT FK_B0A5662FE06A9B8F FOREIGN KEY (laboral_sector_id_id) REFERENCES laboral_sector (id)');
        $this->addSql('CREATE INDEX IDX_B0A5662FE06A9B8F ON laboral_sector_assignments (laboral_sector_id_id)');
        $this->addSql('ALTER TABLE user CHANGE birth_date birth_date BIGINT DEFAULT NULL, CHANGE created created BIGINT DEFAULT NULL');
    }
}
