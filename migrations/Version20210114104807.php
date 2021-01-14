<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210114104807 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE creneau (id_creneau INT AUTO_INCREMENT NOT NULL, id_magasin INT DEFAULT NULL, date DATE NOT NULL, reserver TINYINT(1) NOT NULL, INDEX IDX_F9668B5F8A32F657 (id_magasin), PRIMARY KEY(id_creneau)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE creneau ADD CONSTRAINT FK_F9668B5F8A32F657 FOREIGN KEY (id_magasin) REFERENCES magasin (id)');
        $this->addSql('ALTER TABLE client CHANGE roles roles LONGTEXT NOT NULL COMMENT \'(DC2Type:json)\'');
        $this->addSql('ALTER TABLE commande ADD id_creneau INT DEFAULT NULL, DROP creneau');
        $this->addSql('ALTER TABLE commande ADD CONSTRAINT FK_6EEAA67D27FB222F FOREIGN KEY (id_creneau) REFERENCES creneau (id_creneau)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_6EEAA67D27FB222F ON commande (id_creneau)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE commande DROP FOREIGN KEY FK_6EEAA67D27FB222F');
        $this->addSql('DROP TABLE creneau');
        $this->addSql('ALTER TABLE client CHANGE roles roles LONGTEXT CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_bin`');
        $this->addSql('DROP INDEX UNIQ_6EEAA67D27FB222F ON commande');
        $this->addSql('ALTER TABLE commande ADD creneau DATETIME DEFAULT NULL, DROP id_creneau');
    }
}
