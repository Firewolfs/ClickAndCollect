<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210115124436 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE commande DROP FOREIGN KEY FK_6EEAA67D20096AE3');
        $this->addSql('ALTER TABLE commande ADD CONSTRAINT FK_6EEAA67D20096AE3 FOREIGN KEY (magasin_id) REFERENCES magasin (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE creneau DROP FOREIGN KEY FK_F9668B5F8A32F657');
        $this->addSql('ALTER TABLE creneau ADD CONSTRAINT FK_F9668B5F8A32F657 FOREIGN KEY (id_magasin) REFERENCES magasin (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE ligne_commande DROP FOREIGN KEY FK_3170B74B82EA2E54');
        $this->addSql('ALTER TABLE ligne_commande ADD CONSTRAINT FK_3170B74B82EA2E54 FOREIGN KEY (commande_id) REFERENCES commande (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE stocks DROP FOREIGN KEY FK_56F7980520096AE3');
        $this->addSql('ALTER TABLE stocks ADD CONSTRAINT FK_56F7980520096AE3 FOREIGN KEY (magasin_id) REFERENCES magasin (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE vendeur DROP FOREIGN KEY FK_7AF4999620096AE3');
        $this->addSql('ALTER TABLE vendeur ADD CONSTRAINT FK_7AF4999620096AE3 FOREIGN KEY (magasin_id) REFERENCES magasin (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE commande DROP FOREIGN KEY FK_6EEAA67D20096AE3');
        $this->addSql('ALTER TABLE commande ADD CONSTRAINT FK_6EEAA67D20096AE3 FOREIGN KEY (magasin_id) REFERENCES magasin (id)');
        $this->addSql('ALTER TABLE creneau DROP FOREIGN KEY FK_F9668B5F8A32F657');
        $this->addSql('ALTER TABLE creneau ADD CONSTRAINT FK_F9668B5F8A32F657 FOREIGN KEY (id_magasin) REFERENCES magasin (id)');
        $this->addSql('ALTER TABLE ligne_commande DROP FOREIGN KEY FK_3170B74B82EA2E54');
        $this->addSql('ALTER TABLE ligne_commande ADD CONSTRAINT FK_3170B74B82EA2E54 FOREIGN KEY (commande_id) REFERENCES commande (id)');
        $this->addSql('ALTER TABLE stocks DROP FOREIGN KEY FK_56F7980520096AE3');
        $this->addSql('ALTER TABLE stocks ADD CONSTRAINT FK_56F7980520096AE3 FOREIGN KEY (magasin_id) REFERENCES magasin (id)');
        $this->addSql('ALTER TABLE vendeur DROP FOREIGN KEY FK_7AF4999620096AE3');
        $this->addSql('ALTER TABLE vendeur ADD CONSTRAINT FK_7AF4999620096AE3 FOREIGN KEY (magasin_id) REFERENCES magasin (id)');
    }
}
