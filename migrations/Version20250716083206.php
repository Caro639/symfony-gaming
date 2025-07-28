<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250716083206 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE user_own_game DROP FOREIGN KEY FK_2F79BF7BA76ED395');
        $this->addSql('ALTER TABLE user_own_game DROP FOREIGN KEY FK_2F79BF7BE48FD905');
        $this->addSql('ALTER TABLE user_own_game ADD CONSTRAINT FK_2F79BF7BA76ED395 FOREIGN KEY (user_id) REFERENCES user (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE user_own_game ADD CONSTRAINT FK_2F79BF7BE48FD905 FOREIGN KEY (game_id) REFERENCES game (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE user_own_game DROP FOREIGN KEY FK_2F79BF7BE48FD905');
        $this->addSql('ALTER TABLE user_own_game DROP FOREIGN KEY FK_2F79BF7BA76ED395');
        $this->addSql('ALTER TABLE user_own_game ADD CONSTRAINT FK_2F79BF7BE48FD905 FOREIGN KEY (game_id) REFERENCES game (id)');
        $this->addSql('ALTER TABLE user_own_game ADD CONSTRAINT FK_2F79BF7BA76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
    }
}
