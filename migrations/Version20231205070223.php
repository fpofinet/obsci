<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20231205070223 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE resultat ADD autor_id INT DEFAULT NULL, ADD validator_id INT DEFAULT NULL, ADD created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', ADD valided_on DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\'');
        $this->addSql('ALTER TABLE resultat ADD CONSTRAINT FK_E7DB5DE214D45BBE FOREIGN KEY (autor_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE resultat ADD CONSTRAINT FK_E7DB5DE2B0644AEC FOREIGN KEY (validator_id) REFERENCES user (id)');
        $this->addSql('CREATE INDEX IDX_E7DB5DE214D45BBE ON resultat (autor_id)');
        $this->addSql('CREATE INDEX IDX_E7DB5DE2B0644AEC ON resultat (validator_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE resultat DROP FOREIGN KEY FK_E7DB5DE214D45BBE');
        $this->addSql('ALTER TABLE resultat DROP FOREIGN KEY FK_E7DB5DE2B0644AEC');
        $this->addSql('DROP INDEX IDX_E7DB5DE214D45BBE ON resultat');
        $this->addSql('DROP INDEX IDX_E7DB5DE2B0644AEC ON resultat');
        $this->addSql('ALTER TABLE resultat DROP autor_id, DROP validator_id, DROP created_at, DROP valided_on');
    }
}
