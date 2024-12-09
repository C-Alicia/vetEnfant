<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20241209174401 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE transaction (id INT AUTO_INCREMENT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE address ADD content LONGTEXT NOT NULL, ADD created_at DATETIME NOT NULL, ADD price DOUBLE PRECISION NOT NULL, ADD accepted_date DATETIME NOT NULL, ADD shipping_date DATETIME NOT NULL, ADD delivered_date DATETIME NOT NULL, ADD discount DOUBLE PRECISION NOT NULL, ADD payment_date DATETIME NOT NULL, ADD card_number VARCHAR(20) NOT NULL, DROP street, DROP street_delivery, DROP zip_code, DROP city');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE transaction');
        $this->addSql('ALTER TABLE address ADD street VARCHAR(255) NOT NULL, ADD street_delivery VARCHAR(255) NOT NULL, ADD zip_code VARCHAR(10) NOT NULL, ADD city VARCHAR(50) NOT NULL, DROP content, DROP created_at, DROP price, DROP accepted_date, DROP shipping_date, DROP delivered_date, DROP discount, DROP payment_date, DROP card_number');
    }
}
