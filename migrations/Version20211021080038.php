<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20211021080038 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP INDEX IDX_364071D737D925CB');
        $this->addSql('DROP INDEX IDX_364071D7A76ED395');
        $this->addSql('CREATE TEMPORARY TABLE __temp__emprunt AS SELECT id, livre_id, user_id, reserve, datestart, dateend, emptrunte FROM emprunt');
        $this->addSql('DROP TABLE emprunt');
        $this->addSql('CREATE TABLE emprunt (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, livre_id INTEGER NOT NULL, user_id INTEGER NOT NULL, reserve BOOLEAN NOT NULL, datestart DATETIME NOT NULL, dateend DATE NOT NULL, emptrunte BOOLEAN NOT NULL, CONSTRAINT FK_364071D737D925CB FOREIGN KEY (livre_id) REFERENCES livre (id) NOT DEFERRABLE INITIALLY IMMEDIATE, CONSTRAINT FK_364071D7A76ED395 FOREIGN KEY (user_id) REFERENCES user (id) NOT DEFERRABLE INITIALLY IMMEDIATE)');
        $this->addSql('INSERT INTO emprunt (id, livre_id, user_id, reserve, datestart, dateend, emptrunte) SELECT id, livre_id, user_id, reserve, datestart, dateend, emptrunte FROM __temp__emprunt');
        $this->addSql('DROP TABLE __temp__emprunt');
        $this->addSql('CREATE INDEX IDX_364071D737D925CB ON emprunt (livre_id)');
        $this->addSql('CREATE INDEX IDX_364071D7A76ED395 ON emprunt (user_id)');
        $this->addSql('CREATE TEMPORARY TABLE __temp__livre AS SELECT id, type, disponible, titre, img, date, description, auteur FROM livre');
        $this->addSql('DROP TABLE livre');
        $this->addSql('CREATE TABLE livre (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, type VARCHAR(255) NOT NULL COLLATE BINARY, disponible BOOLEAN NOT NULL, titre VARCHAR(255) NOT NULL COLLATE BINARY, date DATE NOT NULL, description VARCHAR(255) NOT NULL COLLATE BINARY, auteur VARCHAR(255) NOT NULL COLLATE BINARY, img VARCHAR(255) DEFAULT NULL)');
        $this->addSql('INSERT INTO livre (id, type, disponible, titre, img, date, description, auteur) SELECT id, type, disponible, titre, img, date, description, auteur FROM __temp__livre');
        $this->addSql('DROP TABLE __temp__livre');
        $this->addSql('DROP INDEX IDX_1053AB9E37D925CB');
        $this->addSql('DROP INDEX IDX_1053AB9E4296D31F');
        $this->addSql('CREATE TEMPORARY TABLE __temp__livre_genre AS SELECT livre_id, genre_id FROM livre_genre');
        $this->addSql('DROP TABLE livre_genre');
        $this->addSql('CREATE TABLE livre_genre (livre_id INTEGER NOT NULL, genre_id INTEGER NOT NULL, PRIMARY KEY(livre_id, genre_id), CONSTRAINT FK_1053AB9E37D925CB FOREIGN KEY (livre_id) REFERENCES livre (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE, CONSTRAINT FK_1053AB9E4296D31F FOREIGN KEY (genre_id) REFERENCES genre (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE)');
        $this->addSql('INSERT INTO livre_genre (livre_id, genre_id) SELECT livre_id, genre_id FROM __temp__livre_genre');
        $this->addSql('DROP TABLE __temp__livre_genre');
        $this->addSql('CREATE INDEX IDX_1053AB9E37D925CB ON livre_genre (livre_id)');
        $this->addSql('CREATE INDEX IDX_1053AB9E4296D31F ON livre_genre (genre_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP INDEX IDX_364071D737D925CB');
        $this->addSql('DROP INDEX IDX_364071D7A76ED395');
        $this->addSql('CREATE TEMPORARY TABLE __temp__emprunt AS SELECT id, livre_id, user_id, reserve, datestart, dateend, emptrunte FROM emprunt');
        $this->addSql('DROP TABLE emprunt');
        $this->addSql('CREATE TABLE emprunt (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, livre_id INTEGER NOT NULL, user_id INTEGER NOT NULL, reserve BOOLEAN NOT NULL, datestart DATETIME NOT NULL, dateend DATE NOT NULL, emptrunte BOOLEAN NOT NULL)');
        $this->addSql('INSERT INTO emprunt (id, livre_id, user_id, reserve, datestart, dateend, emptrunte) SELECT id, livre_id, user_id, reserve, datestart, dateend, emptrunte FROM __temp__emprunt');
        $this->addSql('DROP TABLE __temp__emprunt');
        $this->addSql('CREATE INDEX IDX_364071D737D925CB ON emprunt (livre_id)');
        $this->addSql('CREATE INDEX IDX_364071D7A76ED395 ON emprunt (user_id)');
        $this->addSql('CREATE TEMPORARY TABLE __temp__livre AS SELECT id, type, disponible, titre, img, date, description, auteur FROM livre');
        $this->addSql('DROP TABLE livre');
        $this->addSql('CREATE TABLE livre (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, type VARCHAR(255) NOT NULL, disponible BOOLEAN NOT NULL, titre VARCHAR(255) NOT NULL, date DATE NOT NULL, description VARCHAR(255) NOT NULL, auteur VARCHAR(255) NOT NULL, img VARCHAR(255) NOT NULL COLLATE BINARY)');
        $this->addSql('INSERT INTO livre (id, type, disponible, titre, img, date, description, auteur) SELECT id, type, disponible, titre, img, date, description, auteur FROM __temp__livre');
        $this->addSql('DROP TABLE __temp__livre');
        $this->addSql('DROP INDEX IDX_1053AB9E37D925CB');
        $this->addSql('DROP INDEX IDX_1053AB9E4296D31F');
        $this->addSql('CREATE TEMPORARY TABLE __temp__livre_genre AS SELECT livre_id, genre_id FROM livre_genre');
        $this->addSql('DROP TABLE livre_genre');
        $this->addSql('CREATE TABLE livre_genre (livre_id INTEGER NOT NULL, genre_id INTEGER NOT NULL, PRIMARY KEY(livre_id, genre_id))');
        $this->addSql('INSERT INTO livre_genre (livre_id, genre_id) SELECT livre_id, genre_id FROM __temp__livre_genre');
        $this->addSql('DROP TABLE __temp__livre_genre');
        $this->addSql('CREATE INDEX IDX_1053AB9E37D925CB ON livre_genre (livre_id)');
        $this->addSql('CREATE INDEX IDX_1053AB9E4296D31F ON livre_genre (genre_id)');
    }
}
