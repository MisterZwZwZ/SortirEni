<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210818144050 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE campus (id_campus INT AUTO_INCREMENT NOT NULL, nom_campus VARCHAR(255) NOT NULL, PRIMARY KEY(id_campus)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE etats (id_etat INT AUTO_INCREMENT NOT NULL, libelle VARCHAR(255) NOT NULL, PRIMARY KEY(id_etat)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE lieux (id_lieu INT AUTO_INCREMENT NOT NULL, nom VARCHAR(255) NOT NULL, rue VARCHAR(255) NOT NULL, latitude DOUBLE PRECISION DEFAULT NULL, longitude DOUBLE PRECISION DEFAULT NULL, PRIMARY KEY(id_lieu)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE sorties (id_sortie INT AUTO_INCREMENT NOT NULL, nom VARCHAR(255) NOT NULL, date_heure_debut DATETIME NOT NULL, date_limite_inscription DATETIME NOT NULL, duree INT NOT NULL, nb_incriptions_max INT NOT NULL, infos_sortie TEXT DEFAULT NULL, etat VARCHAR(255) NOT NULL, id_organisateur INT NOT NULL, inscrit LONGTEXT NOT NULL COMMENT \'(DC2Type:array)\', PRIMARY KEY(id_sortie)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE villes (id_ville INT AUTO_INCREMENT NOT NULL, nom VARCHAR(255) NOT NULL, code_postal INT NOT NULL, PRIMARY KEY(id_ville)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE user CHANGE email email VARCHAR(30) NOT NULL, CHANGE nom nom VARCHAR(30) NOT NULL, CHANGE prenom prenom VARCHAR(30) NOT NULL, CHANGE telephone telephone VARCHAR(255) NOT NULL, CHANGE pseudo pseudo VARCHAR(30) NOT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE campus');
        $this->addSql('DROP TABLE etats');
        $this->addSql('DROP TABLE lieux');
        $this->addSql('DROP TABLE sorties');
        $this->addSql('DROP TABLE villes');
        $this->addSql('ALTER TABLE user CHANGE email email VARCHAR(180) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, CHANGE nom nom VARCHAR(50) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, CHANGE prenom prenom VARCHAR(50) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, CHANGE telephone telephone INT NOT NULL, CHANGE pseudo pseudo VARCHAR(50) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`');
    }
}
