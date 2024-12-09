<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20241209182411 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE category ADD products_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE category ADD CONSTRAINT FK_64C19C16C8A81A9 FOREIGN KEY (products_id) REFERENCES product (id)');
        $this->addSql('CREATE INDEX IDX_64C19C16C8A81A9 ON category (products_id)');
        $this->addSql('ALTER TABLE claim ADD id_order_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE claim ADD CONSTRAINT FK_A769DE27DD4481AD FOREIGN KEY (id_order_id) REFERENCES `order` (id)');
        $this->addSql('CREATE INDEX IDX_A769DE27DD4481AD ON claim (id_order_id)');
        $this->addSql('ALTER TABLE image ADD products_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE image ADD CONSTRAINT FK_C53D045F6C8A81A9 FOREIGN KEY (products_id) REFERENCES product (id)');
        $this->addSql('CREATE INDEX IDX_C53D045F6C8A81A9 ON image (products_id)');
        $this->addSql('ALTER TABLE product ADD id_order_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE product ADD CONSTRAINT FK_D34A04ADDD4481AD FOREIGN KEY (id_order_id) REFERENCES `order` (id)');
        $this->addSql('CREATE INDEX IDX_D34A04ADDD4481AD ON product (id_order_id)');
        $this->addSql('ALTER TABLE transaction ADD id_order_id INT NOT NULL');
        $this->addSql('ALTER TABLE transaction ADD CONSTRAINT FK_723705D1DD4481AD FOREIGN KEY (id_order_id) REFERENCES `order` (id)');
        $this->addSql('CREATE INDEX IDX_723705D1DD4481AD ON transaction (id_order_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE category DROP FOREIGN KEY FK_64C19C16C8A81A9');
        $this->addSql('DROP INDEX IDX_64C19C16C8A81A9 ON category');
        $this->addSql('ALTER TABLE category DROP products_id');
        $this->addSql('ALTER TABLE claim DROP FOREIGN KEY FK_A769DE27DD4481AD');
        $this->addSql('DROP INDEX IDX_A769DE27DD4481AD ON claim');
        $this->addSql('ALTER TABLE claim DROP id_order_id');
        $this->addSql('ALTER TABLE image DROP FOREIGN KEY FK_C53D045F6C8A81A9');
        $this->addSql('DROP INDEX IDX_C53D045F6C8A81A9 ON image');
        $this->addSql('ALTER TABLE image DROP products_id');
        $this->addSql('ALTER TABLE product DROP FOREIGN KEY FK_D34A04ADDD4481AD');
        $this->addSql('DROP INDEX IDX_D34A04ADDD4481AD ON product');
        $this->addSql('ALTER TABLE product DROP id_order_id');
        $this->addSql('ALTER TABLE transaction DROP FOREIGN KEY FK_723705D1DD4481AD');
        $this->addSql('DROP INDEX IDX_723705D1DD4481AD ON transaction');
        $this->addSql('ALTER TABLE transaction DROP id_order_id');
    }
}
