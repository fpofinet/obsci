<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20231202152154 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE resultat_superviseur (id INT AUTO_INCREMENT NOT NULL, commune_id INT DEFAULT NULL, code VARCHAR(255) DEFAULT NULL, code_bureau VARCHAR(255) NOT NULL, etat INT DEFAULT NULL, votant INT NOT NULL, suffrage_exprime INT NOT NULL, suffrage_nul INT NOT NULL, vote_oui INT NOT NULL, vote_non INT NOT NULL, image_pv VARCHAR(255) NOT NULL, INDEX IDX_2D6B2006131A4F72 (commune_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE resultat_superviseur ADD CONSTRAINT FK_2D6B2006131A4F72 FOREIGN KEY (commune_id) REFERENCES commune (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE resultat_superviseur DROP FOREIGN KEY FK_2D6B2006131A4F72');
        $this->addSql('DROP TABLE resultat_superviseur');
    }
}
