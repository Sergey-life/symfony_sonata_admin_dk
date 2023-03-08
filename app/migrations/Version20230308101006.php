<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230308101006 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE store_product (id INT AUTO_INCREMENT NOT NULL, store_id INT NOT NULL, product_id INT NOT NULL, price DOUBLE PRECISION NOT NULL, INDEX IDX_CA42254AB092A811 (store_id), INDEX IDX_CA42254A4584665A (product_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE store_product ADD CONSTRAINT FK_CA42254AB092A811 FOREIGN KEY (store_id) REFERENCES store (id)');
        $this->addSql('ALTER TABLE store_product ADD CONSTRAINT FK_CA42254A4584665A FOREIGN KEY (product_id) REFERENCES product (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE store_product DROP FOREIGN KEY FK_CA42254AB092A811');
        $this->addSql('ALTER TABLE store_product DROP FOREIGN KEY FK_CA42254A4584665A');
        $this->addSql('DROP TABLE store_product');
    }
}
