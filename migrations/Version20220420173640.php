<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220420173640 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE centre_camping ADD email VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE tente DROP FOREIGN KEY FK_F24A792C975F3CA8');
        $this->addSql('ALTER TABLE tente ADD CONSTRAINT FK_F24A792C975F3CA8 FOREIGN KEY (centre_camping_id) REFERENCES centre_camping (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE centre_camping DROP email');
        $this->addSql('ALTER TABLE tente DROP FOREIGN KEY FK_F24A792C975F3CA8');
        $this->addSql('ALTER TABLE tente ADD CONSTRAINT FK_F24A792C975F3CA8 FOREIGN KEY (centre_camping_id) REFERENCES centre_camping (id) ON UPDATE CASCADE ON DELETE CASCADE');
    }
}
