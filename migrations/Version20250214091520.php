<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250214091520 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE donation ADD CONSTRAINT FK_31E581A06D8F00D FOREIGN KEY (type_donation_id) REFERENCES type_donation (id)');
        $this->addSql('CREATE INDEX IDX_31E581A06D8F00D ON donation (type_donation_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE donation DROP FOREIGN KEY FK_31E581A06D8F00D');
        $this->addSql('DROP INDEX IDX_31E581A06D8F00D ON donation');
    }
}
