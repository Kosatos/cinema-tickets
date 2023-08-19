<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230819090149 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE cinema ADD description LONGTEXT NOT NULL');
        $this->addSql('ALTER TABLE media_gallery ADD cinema_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE media_gallery ADD CONSTRAINT FK_26FCFE73B4CB84B6 FOREIGN KEY (cinema_id) REFERENCES cinema (id)');
        $this->addSql('CREATE INDEX IDX_26FCFE73B4CB84B6 ON media_gallery (cinema_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE cinema DROP description');
        $this->addSql('ALTER TABLE media_gallery DROP FOREIGN KEY FK_26FCFE73B4CB84B6');
        $this->addSql('DROP INDEX IDX_26FCFE73B4CB84B6 ON media_gallery');
        $this->addSql('ALTER TABLE media_gallery DROP cinema_id');
    }
}
