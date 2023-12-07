<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20231207152201 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE commune (id INT AUTO_INCREMENT NOT NULL, departement_id INT DEFAULT NULL, code VARCHAR(255) NOT NULL, libelle VARCHAR(255) NOT NULL, INDEX IDX_E2E2D1EECCF9E01E (departement_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE departement (id INT AUTO_INCREMENT NOT NULL, province_id INT DEFAULT NULL, libelle VARCHAR(255) NOT NULL, code VARCHAR(255) NOT NULL, INDEX IDX_C1765B63E946114A (province_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE province (id INT AUTO_INCREMENT NOT NULL, libelle VARCHAR(255) NOT NULL, code VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE resultat (id INT AUTO_INCREMENT NOT NULL, commune_id INT DEFAULT NULL, autor_id INT DEFAULT NULL, validator_id INT DEFAULT NULL, code VARCHAR(255) DEFAULT NULL, code_bureau VARCHAR(255) NOT NULL, etat INT DEFAULT NULL, votant INT NOT NULL, suffrage_exprime INT NOT NULL, suffrage_nul INT NOT NULL, vote_oui INT NOT NULL, vote_non INT NOT NULL, image_pv VARCHAR(255) NOT NULL, created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', valided_on DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', agent_saisie VARCHAR(255) NOT NULL, agent_validation VARCHAR(255) NOT NULL, submitter VARCHAR(255) NOT NULL, submitted_on DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', UNIQUE INDEX UNIQ_E7DB5DE269E98666 (code_bureau), INDEX IDX_E7DB5DE2131A4F72 (commune_id), INDEX IDX_E7DB5DE214D45BBE (autor_id), INDEX IDX_E7DB5DE2B0644AEC (validator_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE resultat_kobo (id INT AUTO_INCREMENT NOT NULL, image_pv VARCHAR(255) NOT NULL, code_kobo VARCHAR(255) NOT NULL, submitter VARCHAR(255) DEFAULT NULL, date_submit DATE DEFAULT NULL, etat INT DEFAULT NULL, allowed_to INT DEFAULT NULL, allowed_on DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE resultat_operateur (id INT AUTO_INCREMENT NOT NULL, commune_id INT DEFAULT NULL, autor_id INT DEFAULT NULL, etat INT DEFAULT NULL, suffrage_exprime INT NOT NULL, suffrage_nul INT NOT NULL, votant INT NOT NULL, vote_oui INT NOT NULL, vote_non INT NOT NULL, created_at DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\', code VARCHAR(255) DEFAULT NULL, image_pv VARCHAR(255) NOT NULL, code_bureau VARCHAR(255) NOT NULL, agent_saisie VARCHAR(255) NOT NULL, submitter VARCHAR(255) NOT NULL, submitted_on DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', validateur INT NOT NULL, INDEX IDX_D451D13E131A4F72 (commune_id), INDEX IDX_D451D13E14D45BBE (autor_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE resultat_superviseur (id INT AUTO_INCREMENT NOT NULL, commune_id INT DEFAULT NULL, autor_id INT DEFAULT NULL, validator_id INT DEFAULT NULL, code VARCHAR(255) DEFAULT NULL, code_bureau VARCHAR(255) NOT NULL, etat INT DEFAULT NULL, votant INT NOT NULL, suffrage_exprime INT NOT NULL, suffrage_nul INT NOT NULL, vote_oui INT NOT NULL, vote_non INT NOT NULL, image_pv VARCHAR(255) NOT NULL, created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', valided_on DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', agent_validation VARCHAR(255) NOT NULL, submitter VARCHAR(255) NOT NULL, submitted_on DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', agent_saisie VARCHAR(255) NOT NULL, INDEX IDX_2D6B2006131A4F72 (commune_id), INDEX IDX_2D6B200614D45BBE (autor_id), INDEX IDX_2D6B2006B0644AEC (validator_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user (id INT AUTO_INCREMENT NOT NULL, username VARCHAR(180) NOT NULL, roles JSON NOT NULL, password VARCHAR(255) NOT NULL, status INT DEFAULT NULL, nom VARCHAR(255) DEFAULT NULL, prenom VARCHAR(255) DEFAULT NULL, email VARCHAR(255) DEFAULT NULL, validateur INT DEFAULT NULL, sexe VARCHAR(255) NOT NULL, telephone VARCHAR(255) DEFAULT NULL, UNIQUE INDEX UNIQ_8D93D649F85E0677 (username), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE commune ADD CONSTRAINT FK_E2E2D1EECCF9E01E FOREIGN KEY (departement_id) REFERENCES departement (id)');
        $this->addSql('ALTER TABLE departement ADD CONSTRAINT FK_C1765B63E946114A FOREIGN KEY (province_id) REFERENCES province (id)');
        $this->addSql('ALTER TABLE resultat ADD CONSTRAINT FK_E7DB5DE2131A4F72 FOREIGN KEY (commune_id) REFERENCES commune (id)');
        $this->addSql('ALTER TABLE resultat ADD CONSTRAINT FK_E7DB5DE214D45BBE FOREIGN KEY (autor_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE resultat ADD CONSTRAINT FK_E7DB5DE2B0644AEC FOREIGN KEY (validator_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE resultat_operateur ADD CONSTRAINT FK_D451D13E131A4F72 FOREIGN KEY (commune_id) REFERENCES commune (id)');
        $this->addSql('ALTER TABLE resultat_operateur ADD CONSTRAINT FK_D451D13E14D45BBE FOREIGN KEY (autor_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE resultat_superviseur ADD CONSTRAINT FK_2D6B2006131A4F72 FOREIGN KEY (commune_id) REFERENCES commune (id)');
        $this->addSql('ALTER TABLE resultat_superviseur ADD CONSTRAINT FK_2D6B200614D45BBE FOREIGN KEY (autor_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE resultat_superviseur ADD CONSTRAINT FK_2D6B2006B0644AEC FOREIGN KEY (validator_id) REFERENCES user (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE commune DROP FOREIGN KEY FK_E2E2D1EECCF9E01E');
        $this->addSql('ALTER TABLE departement DROP FOREIGN KEY FK_C1765B63E946114A');
        $this->addSql('ALTER TABLE resultat DROP FOREIGN KEY FK_E7DB5DE2131A4F72');
        $this->addSql('ALTER TABLE resultat DROP FOREIGN KEY FK_E7DB5DE214D45BBE');
        $this->addSql('ALTER TABLE resultat DROP FOREIGN KEY FK_E7DB5DE2B0644AEC');
        $this->addSql('ALTER TABLE resultat_operateur DROP FOREIGN KEY FK_D451D13E131A4F72');
        $this->addSql('ALTER TABLE resultat_operateur DROP FOREIGN KEY FK_D451D13E14D45BBE');
        $this->addSql('ALTER TABLE resultat_superviseur DROP FOREIGN KEY FK_2D6B2006131A4F72');
        $this->addSql('ALTER TABLE resultat_superviseur DROP FOREIGN KEY FK_2D6B200614D45BBE');
        $this->addSql('ALTER TABLE resultat_superviseur DROP FOREIGN KEY FK_2D6B2006B0644AEC');
        $this->addSql('DROP TABLE commune');
        $this->addSql('DROP TABLE departement');
        $this->addSql('DROP TABLE province');
        $this->addSql('DROP TABLE resultat');
        $this->addSql('DROP TABLE resultat_kobo');
        $this->addSql('DROP TABLE resultat_operateur');
        $this->addSql('DROP TABLE resultat_superviseur');
        $this->addSql('DROP TABLE user');
    }
}
