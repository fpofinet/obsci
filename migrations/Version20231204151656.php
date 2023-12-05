<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20231204151656 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE resultat_operateur ADD autor_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE resultat_operateur ADD CONSTRAINT FK_D451D13E14D45BBE FOREIGN KEY (autor_id) REFERENCES user (id)');
        $this->addSql('CREATE INDEX IDX_D451D13E14D45BBE ON resultat_operateur (autor_id)');
        $this->addSql('ALTER TABLE resultat_superviseur ADD autor_id INT DEFAULT NULL, ADD validator_id INT DEFAULT NULL, ADD created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', ADD valided_on DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\'');
        $this->addSql('ALTER TABLE resultat_superviseur ADD CONSTRAINT FK_2D6B200614D45BBE FOREIGN KEY (autor_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE resultat_superviseur ADD CONSTRAINT FK_2D6B2006B0644AEC FOREIGN KEY (validator_id) REFERENCES user (id)');
        $this->addSql('CREATE INDEX IDX_2D6B200614D45BBE ON resultat_superviseur (autor_id)');
        $this->addSql('CREATE INDEX IDX_2D6B2006B0644AEC ON resultat_superviseur (validator_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE resultat_operateur DROP FOREIGN KEY FK_D451D13E14D45BBE');
        $this->addSql('DROP INDEX IDX_D451D13E14D45BBE ON resultat_operateur');
        $this->addSql('ALTER TABLE resultat_operateur DROP autor_id');
        $this->addSql('ALTER TABLE resultat_superviseur DROP FOREIGN KEY FK_2D6B200614D45BBE');
        $this->addSql('ALTER TABLE resultat_superviseur DROP FOREIGN KEY FK_2D6B2006B0644AEC');
        $this->addSql('DROP INDEX IDX_2D6B200614D45BBE ON resultat_superviseur');
        $this->addSql('DROP INDEX IDX_2D6B2006B0644AEC ON resultat_superviseur');
        $this->addSql('ALTER TABLE resultat_superviseur DROP autor_id, DROP validator_id, DROP created_at, DROP valided_on');
    }
}
