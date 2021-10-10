<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20211009135150 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE emprunt (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, livre_id INTEGER NOT NULL, user_id INTEGER NOT NULL, reserve BOOLEAN NOT NULL, datestart DATETIME NOT NULL, dateend DATE NOT NULL, emptrunte BOOLEAN NOT NULL)');
        $this->addSql('CREATE INDEX IDX_364071D737D925CB ON emprunt (livre_id)');
        $this->addSql('CREATE INDEX IDX_364071D7A76ED395 ON emprunt (user_id)');
        $this->addSql('CREATE TABLE genre (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, titre VARCHAR(255) NOT NULL)');
        $this->addSql('CREATE TABLE livre (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, type VARCHAR(255) NOT NULL, disponible BOOLEAN NOT NULL, titre VARCHAR(255) NOT NULL, img VARCHAR(255) NOT NULL, date DATE NOT NULL, description VARCHAR(255) NOT NULL, auteur VARCHAR(255) NOT NULL)');
        $this->addSql('CREATE TABLE livre_genre (livre_id INTEGER NOT NULL, genre_id INTEGER NOT NULL, PRIMARY KEY(livre_id, genre_id))');
        $this->addSql('CREATE INDEX IDX_1053AB9E37D925CB ON livre_genre (livre_id)');
        $this->addSql('CREATE INDEX IDX_1053AB9E4296D31F ON livre_genre (genre_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE emprunt');
        $this->addSql('DROP TABLE genre');
        $this->addSql('DROP TABLE livre');
        $this->addSql('DROP TABLE livre_genre');
    }
}
