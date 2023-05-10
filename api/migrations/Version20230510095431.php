<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230510095431 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE recepe_user (recepe_id INT NOT NULL, user_id INT NOT NULL, PRIMARY KEY(recepe_id, user_id))');
        $this->addSql('CREATE INDEX IDX_A52799D92E1A626F ON recepe_user (recepe_id)');
        $this->addSql('CREATE INDEX IDX_A52799D9A76ED395 ON recepe_user (user_id)');
        $this->addSql('ALTER TABLE recepe_user ADD CONSTRAINT FK_A52799D92E1A626F FOREIGN KEY (recepe_id) REFERENCES recepe (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE recepe_user ADD CONSTRAINT FK_A52799D9A76ED395 FOREIGN KEY (user_id) REFERENCES "user" (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE recepe_user DROP CONSTRAINT FK_A52799D92E1A626F');
        $this->addSql('ALTER TABLE recepe_user DROP CONSTRAINT FK_A52799D9A76ED395');
        $this->addSql('DROP TABLE recepe_user');
    }
}
