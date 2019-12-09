<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20191206221706 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE UNIQUE INDEX unique_category ON category (name)');
        $this->addSql('CREATE UNIQUE INDEX unique_exposition ON exposition (name)');
        $this->addSql('CREATE UNIQUE INDEX unique_artiste ON artiste (name)');
        $this->addSql('CREATE UNIQUE INDEX unique_oeuvre ON oeuvre (name)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('DROP INDEX unique_artiste ON artiste');
        $this->addSql('DROP INDEX unique_category ON category');
        $this->addSql('DROP INDEX unique_exposition ON exposition');
        $this->addSql('DROP INDEX unique_oeuvre ON oeuvre');
    }
}
