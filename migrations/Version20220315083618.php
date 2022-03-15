<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220315083618 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE characters ADD gls_kind VARCHAR(16) NOT NULL');
        $this->addSql('ALTER TABLE characters ADD gls_name VARCHAR(16) NOT NULL');
        $this->addSql('ALTER TABLE characters ADD gls_caste VARCHAR(16) DEFAULT NULL');
        $this->addSql('ALTER TABLE characters ADD gls_knowledge VARCHAR(16) DEFAULT NULL');
        $this->addSql('ALTER TABLE characters ADD gls_intelligence INT DEFAULT NULL');
        $this->addSql('ALTER TABLE characters ADD gls_life INT DEFAULT NULL');
        $this->addSql('ALTER TABLE characters ADD gls_creation TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL');
        $this->addSql('ALTER TABLE characters ADD gls_modification TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL');
        $this->addSql('ALTER TABLE characters DROP name');
        $this->addSql('ALTER TABLE characters DROP caste');
        $this->addSql('ALTER TABLE characters DROP knowledge');
        $this->addSql('ALTER TABLE characters DROP intelligence');
        $this->addSql('ALTER TABLE characters DROP life');
        $this->addSql('ALTER TABLE characters DROP kind');
        $this->addSql('ALTER TABLE characters DROP creation');
        $this->addSql('ALTER TABLE characters DROP modification');
        $this->addSql('ALTER TABLE characters RENAME COLUMN identifier TO gls_identifier');
        $this->addSql('ALTER TABLE characters RENAME COLUMN surname TO gls_surname');
        $this->addSql('ALTER TABLE characters RENAME COLUMN image TO gls_image');
        $this->addSql('ALTER TABLE player ADD gls_firstname VARCHAR(50) NOT NULL');
        $this->addSql('ALTER TABLE player ADD gls_lastname VARCHAR(50) NOT NULL');
        $this->addSql('ALTER TABLE player ADD gls_email VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE player ADD gls_mirian INT DEFAULT NULL');
        $this->addSql('ALTER TABLE player ADD gls_modification TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL');
        $this->addSql('ALTER TABLE player DROP firstname');
        $this->addSql('ALTER TABLE player DROP lastname');
        $this->addSql('ALTER TABLE player DROP email');
        $this->addSql('ALTER TABLE player DROP mirian');
        $this->addSql('ALTER TABLE player RENAME COLUMN identifier TO gls_identifier');
        $this->addSql('ALTER TABLE player RENAME COLUMN creation TO gls_creation');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE player ADD firstname VARCHAR(45) NOT NULL');
        $this->addSql('ALTER TABLE player ADD lastname VARCHAR(45) NOT NULL');
        $this->addSql('ALTER TABLE player ADD email VARCHAR(100) NOT NULL');
        $this->addSql('ALTER TABLE player ADD mirian INT NOT NULL');
        $this->addSql('ALTER TABLE player ADD creation TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL');
        $this->addSql('ALTER TABLE player DROP gls_firstname');
        $this->addSql('ALTER TABLE player DROP gls_lastname');
        $this->addSql('ALTER TABLE player DROP gls_email');
        $this->addSql('ALTER TABLE player DROP gls_mirian');
        $this->addSql('ALTER TABLE player DROP gls_creation');
        $this->addSql('ALTER TABLE player DROP gls_modification');
        $this->addSql('ALTER TABLE player RENAME COLUMN gls_identifier TO identifier');
        $this->addSql('ALTER TABLE characters ADD name VARCHAR(16) NOT NULL');
        $this->addSql('ALTER TABLE characters ADD caste VARCHAR(16) DEFAULT NULL');
        $this->addSql('ALTER TABLE characters ADD knowledge VARCHAR(16) DEFAULT NULL');
        $this->addSql('ALTER TABLE characters ADD intelligence INT DEFAULT NULL');
        $this->addSql('ALTER TABLE characters ADD life INT DEFAULT NULL');
        $this->addSql('ALTER TABLE characters ADD kind VARCHAR(16) NOT NULL');
        $this->addSql('ALTER TABLE characters ADD creation TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL');
        $this->addSql('ALTER TABLE characters ADD modification TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL');
        $this->addSql('ALTER TABLE characters DROP gls_kind');
        $this->addSql('ALTER TABLE characters DROP gls_name');
        $this->addSql('ALTER TABLE characters DROP gls_caste');
        $this->addSql('ALTER TABLE characters DROP gls_knowledge');
        $this->addSql('ALTER TABLE characters DROP gls_intelligence');
        $this->addSql('ALTER TABLE characters DROP gls_life');
        $this->addSql('ALTER TABLE characters DROP gls_creation');
        $this->addSql('ALTER TABLE characters DROP gls_modification');
        $this->addSql('ALTER TABLE characters RENAME COLUMN gls_surname TO surname');
        $this->addSql('ALTER TABLE characters RENAME COLUMN gls_image TO image');
        $this->addSql('ALTER TABLE characters RENAME COLUMN gls_identifier TO identifier');
    }
}
