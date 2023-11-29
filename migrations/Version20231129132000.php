<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20231129132000 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE temp_resultat DROP nombre_votant, DROP bulletin_nuls, DROP suffrage_exprime, DROP vote_oui, DROP vote_non');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE temp_resultat ADD nombre_votant INT DEFAULT NULL, ADD bulletin_nuls INT DEFAULT NULL, ADD suffrage_exprime INT DEFAULT NULL, ADD vote_oui INT DEFAULT NULL, ADD vote_non INT DEFAULT NULL');
    }
}
