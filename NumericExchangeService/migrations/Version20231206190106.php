<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20231206190106 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE history ALTER first_in DROP NOT NULL');
        $this->addSql('ALTER TABLE history ALTER second_in DROP NOT NULL');
        $this->addSql('ALTER TABLE history ALTER first_out DROP NOT NULL');
        $this->addSql('ALTER TABLE history ALTER second_out DROP NOT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE history ALTER first_in SET NOT NULL');
        $this->addSql('ALTER TABLE history ALTER second_in SET NOT NULL');
        $this->addSql('ALTER TABLE history ALTER first_out SET NOT NULL');
        $this->addSql('ALTER TABLE history ALTER second_out SET NOT NULL');
    }
}
