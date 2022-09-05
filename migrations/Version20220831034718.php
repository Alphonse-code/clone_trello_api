<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220831034718 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE tasks ADD projets_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE tasks ADD CONSTRAINT FK_50586597597A6CB7 FOREIGN KEY (projets_id) REFERENCES project (id)');
        $this->addSql('CREATE INDEX IDX_50586597597A6CB7 ON tasks (projets_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE tasks DROP FOREIGN KEY FK_50586597597A6CB7');
        $this->addSql('DROP INDEX IDX_50586597597A6CB7 ON tasks');
        $this->addSql('ALTER TABLE tasks DROP projets_id');
    }
}
