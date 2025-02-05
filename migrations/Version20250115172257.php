<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250115172257 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE image DROP FOREIGN KEY FK_C53D045F4584665A');
        $this->addSql('ALTER TABLE image CHANGE product_id product_id INT NOT NULL');
        $this->addSql('ALTER TABLE image ADD CONSTRAINT FK_C53D045F4584665A FOREIGN KEY (product_id) REFERENCES product (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE product CHANGE is_sold is_sold TINYINT(1) DEFAULT NULL');
        $this->addSql('ALTER TABLE user CHANGE is_active is_active TINYINT(1) DEFAULT NULL, CHANGE is_role is_role TINYINT(1) DEFAULT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE image DROP FOREIGN KEY FK_C53D045F4584665A');
        $this->addSql('ALTER TABLE image CHANGE product_id product_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE image ADD CONSTRAINT FK_C53D045F4584665A FOREIGN KEY (product_id) REFERENCES product (id)');
        $this->addSql('ALTER TABLE product CHANGE is_sold is_sold TINYINT(1) DEFAULT NULL COMMENT \'0: Actif 1:Vendu\'');
        $this->addSql('ALTER TABLE user CHANGE is_active is_active TINYINT(1) DEFAULT NULL COMMENT \'0: Compte actif 1: compte non actif\', CHANGE is_role is_role TINYINT(1) DEFAULT NULL COMMENT \'0: Acheteur 1: Vendeur\'');
    }
}
