<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20231205084229 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE resultat ADD agent_saisie VARCHAR(255) NOT NULL, ADD agent_validation VARCHAR(255) NOT NULL, ADD submitter VARCHAR(255) NOT NULL, ADD submitted_on DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\'');
        $this->addSql('ALTER TABLE resultat_operateur ADD agent_saisie VARCHAR(255) NOT NULL, ADD submitter VARCHAR(255) NOT NULL, ADD submitted_on DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\'');
        $this->addSql('ALTER TABLE resultat_superviseur ADD agent_validation VARCHAR(255) NOT NULL, ADD submitter VARCHAR(255) NOT NULL, ADD submitted_on DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', ADD agent_saisie VARCHAR(255) NOT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE resultat_superviseur DROP agent_validation, DROP submitter, DROP submitted_on, DROP agent_saisie');
        $this->addSql('ALTER TABLE resultat_operateur DROP agent_saisie, DROP submitter, DROP submitted_on');
        $this->addSql('ALTER TABLE resultat DROP agent_saisie, DROP agent_validation, DROP submitter, DROP submitted_on');
    }
}
