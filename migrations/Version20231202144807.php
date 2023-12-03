<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20231202144807 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE resultat_operateur ADD commune_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE resultat_operateur ADD CONSTRAINT FK_D451D13E131A4F72 FOREIGN KEY (commune_id) REFERENCES commune (id)');
        $this->addSql('CREATE INDEX IDX_D451D13E131A4F72 ON resultat_operateur (commune_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE resultat_operateur DROP FOREIGN KEY FK_D451D13E131A4F72');
        $this->addSql('DROP INDEX IDX_D451D13E131A4F72 ON resultat_operateur');
        $this->addSql('ALTER TABLE resultat_operateur DROP commune_id');
    }
}
