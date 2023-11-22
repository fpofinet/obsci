<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20231122131222 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE bureau_vote (id INT AUTO_INCREMENT NOT NULL, commune_id INT DEFAULT NULL, code VARCHAR(255) NOT NULL, INDEX IDX_82384C04131A4F72 (commune_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE commune (id INT AUTO_INCREMENT NOT NULL, departement_id INT DEFAULT NULL, code VARCHAR(255) NOT NULL, libelle VARCHAR(255) NOT NULL, INDEX IDX_E2E2D1EECCF9E01E (departement_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE departement (id INT AUTO_INCREMENT NOT NULL, province_id INT DEFAULT NULL, libelle VARCHAR(255) NOT NULL, INDEX IDX_C1765B63E946114A (province_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE province (id INT AUTO_INCREMENT NOT NULL, libelle VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE resultat (id INT AUTO_INCREMENT NOT NULL, bureau_vote_id INT DEFAULT NULL, code VARCHAR(255) NOT NULL, etat INT DEFAULT NULL, votant INT NOT NULL, suffrage_exprime INT NOT NULL, suffrage_nul INT NOT NULL, vote_oui INT NOT NULL, vote_non INT NOT NULL, proces_verbal VARCHAR(255) NOT NULL, INDEX IDX_E7DB5DE21586D5F9 (bureau_vote_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE temp_resultat (id INT AUTO_INCREMENT NOT NULL, province VARCHAR(255) DEFAULT NULL, departement VARCHAR(255) DEFAULT NULL, commune VARCHAR(255) DEFAULT NULL, bureau_vote VARCHAR(255) DEFAULT NULL, nombre_votant INT DEFAULT NULL, bulletin_nuls INT DEFAULT NULL, suffrage_exprime INT DEFAULT NULL, vote_oui INT DEFAULT NULL, vote_non INT DEFAULT NULL, proces_verbal VARCHAR(255) DEFAULT NULL, date DATETIME NOT NULL, id_submitter VARCHAR(255) DEFAULT NULL, code_kobo VARCHAR(255) DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE bureau_vote ADD CONSTRAINT FK_82384C04131A4F72 FOREIGN KEY (commune_id) REFERENCES commune (id)');
        $this->addSql('ALTER TABLE commune ADD CONSTRAINT FK_E2E2D1EECCF9E01E FOREIGN KEY (departement_id) REFERENCES departement (id)');
        $this->addSql('ALTER TABLE departement ADD CONSTRAINT FK_C1765B63E946114A FOREIGN KEY (province_id) REFERENCES province (id)');
        $this->addSql('ALTER TABLE resultat ADD CONSTRAINT FK_E7DB5DE21586D5F9 FOREIGN KEY (bureau_vote_id) REFERENCES bureau_vote (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE bureau_vote DROP FOREIGN KEY FK_82384C04131A4F72');
        $this->addSql('ALTER TABLE commune DROP FOREIGN KEY FK_E2E2D1EECCF9E01E');
        $this->addSql('ALTER TABLE departement DROP FOREIGN KEY FK_C1765B63E946114A');
        $this->addSql('ALTER TABLE resultat DROP FOREIGN KEY FK_E7DB5DE21586D5F9');
        $this->addSql('DROP TABLE bureau_vote');
        $this->addSql('DROP TABLE commune');
        $this->addSql('DROP TABLE departement');
        $this->addSql('DROP TABLE province');
        $this->addSql('DROP TABLE resultat');
        $this->addSql('DROP TABLE temp_resultat');
    }
}
