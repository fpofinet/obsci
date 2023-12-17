<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20231217044102 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE commune ADD nombre_bureau INT DEFAULT NULL');
        $this->addSql('ALTER TABLE resultat ADD quartier VARCHAR(255) DEFAULT NULL, ADD numero_bureau_vote INT DEFAULT NULL, ADD inscrit INT DEFAULT NULL');
        $this->addSql('ALTER TABLE resultat_operateur ADD quartier VARCHAR(255) DEFAULT NULL, ADD numero_bureau_vote INT DEFAULT NULL, ADD inscrit INT DEFAULT NULL');
        $this->addSql('ALTER TABLE resultat_superviseur ADD quartier VARCHAR(255) DEFAULT NULL, ADD numero_bureau_vote INT DEFAULT NULL, ADD inscrit INT DEFAULT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE commune DROP nombre_bureau');
        $this->addSql('ALTER TABLE resultat_superviseur DROP quartier, DROP numero_bureau_vote, DROP inscrit');
        $this->addSql('ALTER TABLE resultat_operateur DROP quartier, DROP numero_bureau_vote, DROP inscrit');
        $this->addSql('ALTER TABLE resultat DROP quartier, DROP numero_bureau_vote, DROP inscrit');
    }
}
