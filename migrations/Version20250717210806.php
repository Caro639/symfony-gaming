<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250717210806 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE publisher DROP FOREIGN KEY FK_9CE8D546F92F3E70');
        $this->addSql('ALTER TABLE publisher ADD CONSTRAINT FK_9CE8D546F92F3E70F92F3E70 FOREIGN KEY (country_id) REFERENCES country (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE publisher DROP FOREIGN KEY FK_9CE8D546F92F3E70F92F3E70');
        $this->addSql('ALTER TABLE publisher ADD CONSTRAINT FK_9CE8D546F92F3E70 FOREIGN KEY (country_id) REFERENCES country (id)');
    }
}
