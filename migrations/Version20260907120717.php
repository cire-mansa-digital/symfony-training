<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20260907120717 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE recipe ADD ruser_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE recipe ADD CONSTRAINT FK_DA88B137B155456F FOREIGN KEY (ruser_id) REFERENCES "user" (id)');
        $this->addSql('CREATE INDEX IDX_DA88B137B155456F ON recipe (ruser_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE recipe DROP CONSTRAINT FK_DA88B137B155456F');
        $this->addSql('DROP INDEX IDX_DA88B137B155456F');
        $this->addSql('ALTER TABLE recipe DROP ruser_id');
    }
}
