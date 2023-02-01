<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230201112421 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE acteur (id INT AUTO_INCREMENT NOT NULL, nom VARCHAR(150) NOT NULL, prenom VARCHAR(150) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE acteur_film (acteur_id INT NOT NULL, film_id INT NOT NULL, INDEX IDX_14B01103DA6F574A (acteur_id), INDEX IDX_14B01103567F5183 (film_id), PRIMARY KEY(acteur_id, film_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE genre (id INT AUTO_INCREMENT NOT NULL, type VARCHAR(50) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE genre_film (genre_id INT NOT NULL, film_id INT NOT NULL, INDEX IDX_39A967D24296D31F (genre_id), INDEX IDX_39A967D2567F5183 (film_id), PRIMARY KEY(genre_id, film_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE realisateur (id INT AUTO_INCREMENT NOT NULL, nom VARCHAR(150) NOT NULL, prenom VARCHAR(150) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE acteur_film ADD CONSTRAINT FK_14B01103DA6F574A FOREIGN KEY (acteur_id) REFERENCES acteur (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE acteur_film ADD CONSTRAINT FK_14B01103567F5183 FOREIGN KEY (film_id) REFERENCES film (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE genre_film ADD CONSTRAINT FK_39A967D24296D31F FOREIGN KEY (genre_id) REFERENCES genre (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE genre_film ADD CONSTRAINT FK_39A967D2567F5183 FOREIGN KEY (film_id) REFERENCES film (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE film ADD realisateur_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE film ADD CONSTRAINT FK_8244BE22F1D8422E FOREIGN KEY (realisateur_id) REFERENCES realisateur (id)');
        $this->addSql('CREATE INDEX IDX_8244BE22F1D8422E ON film (realisateur_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE film DROP FOREIGN KEY FK_8244BE22F1D8422E');
        $this->addSql('ALTER TABLE acteur_film DROP FOREIGN KEY FK_14B01103DA6F574A');
        $this->addSql('ALTER TABLE acteur_film DROP FOREIGN KEY FK_14B01103567F5183');
        $this->addSql('ALTER TABLE genre_film DROP FOREIGN KEY FK_39A967D24296D31F');
        $this->addSql('ALTER TABLE genre_film DROP FOREIGN KEY FK_39A967D2567F5183');
        $this->addSql('DROP TABLE acteur');
        $this->addSql('DROP TABLE acteur_film');
        $this->addSql('DROP TABLE genre');
        $this->addSql('DROP TABLE genre_film');
        $this->addSql('DROP TABLE realisateur');
        $this->addSql('DROP INDEX IDX_8244BE22F1D8422E ON film');
        $this->addSql('ALTER TABLE film DROP realisateur_id');
    }
}
