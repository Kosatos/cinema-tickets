<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230822173844 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE session DROP FOREIGN KEY FK_D044D5D4613FECDF');
        $this->addSql('DROP INDEX IDX_D044D5D4613FECDF ON session');
        $this->addSql('ALTER TABLE session DROP session_id, DROP started_at');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE session ADD session_id INT DEFAULT NULL, ADD started_at VARCHAR(8) DEFAULT NULL');
        $this->addSql('ALTER TABLE session ADD CONSTRAINT FK_D044D5D4613FECDF FOREIGN KEY (session_id) REFERENCES session (id)');
        $this->addSql('CREATE INDEX IDX_D044D5D4613FECDF ON session (session_id)');
    }
}
