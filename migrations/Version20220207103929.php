<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220207103929 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'first migration';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SEQUENCE characters_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE TABLE characters (id INT NOT NULL, name VARCHAR(16) NOT NULL, surname VARCHAR(64) NOT NULL, caste VARCHAR(16) DEFAULT NULL, knowledge VARCHAR(16) DEFAULT NULL, intelligence INT DEFAULT NULL, life INT DEFAULT NULL, image VARCHAR(128) DEFAULT NULL, kind VARCHAR(16) NOT NULL, creation TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, PRIMARY KEY(id))');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('DROP SEQUENCE characters_id_seq CASCADE');
        $this->addSql('DROP TABLE characters');
    }
}
