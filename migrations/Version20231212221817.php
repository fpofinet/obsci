<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20231212221817 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE resultat ADD libelle_bureau_vote VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE resultat_operateur ADD libelle_bureau_vote VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE resultat_superviseur ADD libelle_bureau_vote VARCHAR(255) DEFAULT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE resultat DROP libelle_bureau_vote');
        $this->addSql('ALTER TABLE resultat_operateur DROP libelle_bureau_vote');
        $this->addSql('ALTER TABLE resultat_superviseur DROP libelle_bureau_vote');
    }
}
