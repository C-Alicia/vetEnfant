<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20241209174849 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE address');
        $this->addSql('ALTER TABLE transaction ADD content LONGTEXT NOT NULL, ADD created_at DATETIME NOT NULL, ADD price DOUBLE PRECISION NOT NULL, ADD accepted_date DATETIME NOT NULL, ADD shipping_date DATETIME NOT NULL, ADD delivery_date DATETIME NOT NULL, ADD discount DOUBLE PRECISION NOT NULL, ADD payment_date DATETIME NOT NULL, ADD card_number VARCHAR(20) NOT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE address (id INT AUTO_INCREMENT NOT NULL, content LONGTEXT CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, created_at DATETIME NOT NULL, price DOUBLE PRECISION NOT NULL, accepted_date DATETIME NOT NULL, shipping_date DATETIME NOT NULL, delivered_date DATETIME NOT NULL, discount DOUBLE PRECISION NOT NULL, payment_date DATETIME NOT NULL, card_number VARCHAR(20) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('ALTER TABLE transaction DROP content, DROP created_at, DROP price, DROP accepted_date, DROP shipping_date, DROP delivery_date, DROP discount, DROP payment_date, DROP card_number');
    }
}
