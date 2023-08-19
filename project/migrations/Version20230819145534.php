<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230819145534 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE hall (id INT AUTO_INCREMENT NOT NULL, number INT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE session (id INT AUTO_INCREMENT NOT NULL, session_id INT DEFAULT NULL, hall_id INT DEFAULT NULL, cinema_id INT DEFAULT NULL, data DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\', started_at VARCHAR(8) DEFAULT NULL, INDEX IDX_D044D5D4613FECDF (session_id), INDEX IDX_D044D5D452AFCFD6 (hall_id), INDEX IDX_D044D5D4B4CB84B6 (cinema_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE session ADD CONSTRAINT FK_D044D5D4613FECDF FOREIGN KEY (session_id) REFERENCES session (id)');
        $this->addSql('ALTER TABLE session ADD CONSTRAINT FK_D044D5D452AFCFD6 FOREIGN KEY (hall_id) REFERENCES hall (id)');
        $this->addSql('ALTER TABLE session ADD CONSTRAINT FK_D044D5D4B4CB84B6 FOREIGN KEY (cinema_id) REFERENCES cinema (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE session DROP FOREIGN KEY FK_D044D5D452AFCFD6');
        $this->addSql('ALTER TABLE session DROP FOREIGN KEY FK_D044D5D4613FECDF');
        $this->addSql('DROP TABLE hall');
        $this->addSql('DROP TABLE session');
    }
}
