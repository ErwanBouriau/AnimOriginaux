<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200402150318 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'sqlite', 'Migration can only be executed safely on \'sqlite\'.');

        $this->addSql('DROP INDEX UNIQ_5E9E89CB8E962C16');
        $this->addSql('CREATE TEMPORARY TABLE __temp__location AS SELECT id, animal_id FROM location');
        $this->addSql('DROP TABLE location');
        $this->addSql('CREATE TABLE location (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, animal_id INTEGER NOT NULL, user_id INTEGER NOT NULL, CONSTRAINT FK_5E9E89CBA76ED395 FOREIGN KEY (user_id) REFERENCES user (id) NOT DEFERRABLE INITIALLY IMMEDIATE, CONSTRAINT FK_5E9E89CB8E962C16 FOREIGN KEY (animal_id) REFERENCES animal (id) NOT DEFERRABLE INITIALLY IMMEDIATE)');
        $this->addSql('INSERT INTO location (id, animal_id) SELECT id, animal_id FROM __temp__location');
        $this->addSql('DROP TABLE __temp__location');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_5E9E89CB8E962C16 ON location (animal_id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_5E9E89CBA76ED395 ON location (user_id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'sqlite', 'Migration can only be executed safely on \'sqlite\'.');

        $this->addSql('DROP INDEX UNIQ_5E9E89CBA76ED395');
        $this->addSql('DROP INDEX UNIQ_5E9E89CB8E962C16');
        $this->addSql('CREATE TEMPORARY TABLE __temp__location AS SELECT id, animal_id FROM location');
        $this->addSql('DROP TABLE location');
        $this->addSql('CREATE TABLE location (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, animal_id INTEGER NOT NULL)');
        $this->addSql('INSERT INTO location (id, animal_id) SELECT id, animal_id FROM __temp__location');
        $this->addSql('DROP TABLE __temp__location');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_5E9E89CB8E962C16 ON location (animal_id)');
    }
}
