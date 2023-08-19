<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230819091757 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE cinema_country (cinema_id INT NOT NULL, country_id INT NOT NULL, INDEX IDX_65996BBAB4CB84B6 (cinema_id), INDEX IDX_65996BBAF92F3E70 (country_id), PRIMARY KEY(cinema_id, country_id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE country (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, slug VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE cinema_country ADD CONSTRAINT FK_65996BBAB4CB84B6 FOREIGN KEY (cinema_id) REFERENCES cinema (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE cinema_country ADD CONSTRAINT FK_65996BBAF92F3E70 FOREIGN KEY (country_id) REFERENCES country (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE cinema_country DROP FOREIGN KEY FK_65996BBAF92F3E70');
        $this->addSql('DROP TABLE cinema_country');
        $this->addSql('DROP TABLE country');
    }
}
