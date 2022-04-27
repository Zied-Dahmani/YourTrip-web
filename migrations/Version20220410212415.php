<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220410212415 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE tente ADD centre_camping_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE tente ADD CONSTRAINT FK_F24A792C975F3CA8 FOREIGN KEY (centre_camping_id) REFERENCES centre_camping (id)');
        $this->addSql('CREATE INDEX IDX_F24A792C975F3CA8 ON tente (centre_camping_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE tente DROP FOREIGN KEY FK_F24A792C975F3CA8');
        $this->addSql('DROP INDEX IDX_F24A792C975F3CA8 ON tente');
        $this->addSql('ALTER TABLE tente DROP centre_camping_id');
    }
}
