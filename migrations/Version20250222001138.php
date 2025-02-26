<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250222001138 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE type_donation (id INT AUTO_INCREMENT NOT NULL, type_de_donation VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE demande DROP FOREIGN KEY FK_2694D7A5A76ED395');
        $this->addSql('DROP INDEX IDX_2694D7A5A76ED395 ON demande');
        $this->addSql('ALTER TABLE demande ADD montant_demande DOUBLE PRECISION DEFAULT NULL, ADD type_song_demader VARCHAR(255) DEFAULT NULL, ADD photo_demande VARCHAR(255) DEFAULT NULL, DROP user_id, DROP type_demande, CHANGE created_at created_at DATETIME DEFAULT CURRENT_TIMESTAMP NOT NULL COMMENT \'(DC2Type:datetime_immutable)\'');
        $this->addSql('ALTER TABLE donation DROP FOREIGN KEY FK_31E581A080E95E18');
        $this->addSql('ALTER TABLE donation DROP FOREIGN KEY FK_31E581A0A76ED395');
        $this->addSql('DROP INDEX IDX_31E581A080E95E18 ON donation');
        $this->addSql('DROP INDEX IDX_31E581A0A76ED395 ON donation');
        $this->addSql('ALTER TABLE donation ADD photo_donation VARCHAR(255) DEFAULT NULL, ADD created_at DATETIME DEFAULT CURRENT_TIMESTAMP NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', ADD updated_at DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\', DROP demande_id, DROP photo_materiel, DROP description, CHANGE user_id type_donation_id INT NOT NULL, CHANGE type_de_donation description_donation VARCHAR(255) NOT NULL, CHANGE montant montant_donation DOUBLE PRECISION DEFAULT NULL');
        $this->addSql('ALTER TABLE donation ADD CONSTRAINT FK_31E581A06D8F00D FOREIGN KEY (type_donation_id) REFERENCES type_donation (id)');
        $this->addSql('CREATE INDEX IDX_31E581A06D8F00D ON donation (type_donation_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE donation DROP FOREIGN KEY FK_31E581A06D8F00D');
        $this->addSql('DROP TABLE type_donation');
        $this->addSql('ALTER TABLE demande ADD user_id INT NOT NULL, ADD type_demande VARCHAR(255) NOT NULL, DROP montant_demande, DROP type_song_demader, DROP photo_demande, CHANGE created_at created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\'');
        $this->addSql('ALTER TABLE demande ADD CONSTRAINT FK_2694D7A5A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('CREATE INDEX IDX_2694D7A5A76ED395 ON demande (user_id)');
        $this->addSql('DROP INDEX IDX_31E581A06D8F00D ON donation');
        $this->addSql('ALTER TABLE donation ADD demande_id INT DEFAULT NULL, ADD description VARCHAR(255) DEFAULT NULL, DROP created_at, DROP updated_at, CHANGE type_donation_id user_id INT NOT NULL, CHANGE description_donation type_de_donation VARCHAR(255) NOT NULL, CHANGE montant_donation montant DOUBLE PRECISION DEFAULT NULL, CHANGE photo_donation photo_materiel VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE donation ADD CONSTRAINT FK_31E581A080E95E18 FOREIGN KEY (demande_id) REFERENCES demande (id)');
        $this->addSql('ALTER TABLE donation ADD CONSTRAINT FK_31E581A0A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('CREATE INDEX IDX_31E581A080E95E18 ON donation (demande_id)');
        $this->addSql('CREATE INDEX IDX_31E581A0A76ED395 ON donation (user_id)');
    }
}
