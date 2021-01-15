<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210115093941 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE creneau CHANGE date date DATETIME NOT NULL');
        $this->addSql('ALTER TABLE stocks DROP FOREIGN KEY FK_56F7980520096AE3');
        $this->addSql('ALTER TABLE stocks ADD CONSTRAINT FK_56F7980520096AE3 FOREIGN KEY (magasin_id) REFERENCES magasin (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE creneau CHANGE date date DATE NOT NULL');
        $this->addSql('ALTER TABLE stocks DROP FOREIGN KEY FK_56F7980520096AE3');
        $this->addSql('ALTER TABLE stocks ADD CONSTRAINT FK_56F7980520096AE3 FOREIGN KEY (magasin_id) REFERENCES magasin (id)');
    }
}
