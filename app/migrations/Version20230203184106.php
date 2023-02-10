<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230203184106 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE basket_item ADD order_ref_id INT NOT NULL');
        $this->addSql('ALTER TABLE basket_item ADD CONSTRAINT FK_D4943C2BE238517C FOREIGN KEY (order_ref_id) REFERENCES `order` (id)');
        $this->addSql('CREATE INDEX IDX_D4943C2BE238517C ON basket_item (order_ref_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE basket_item DROP FOREIGN KEY FK_D4943C2BE238517C');
        $this->addSql('DROP INDEX IDX_D4943C2BE238517C ON basket_item');
        $this->addSql('ALTER TABLE basket_item DROP order_ref_id');
    }
}
