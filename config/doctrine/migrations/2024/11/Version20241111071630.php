<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20241111071630 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Items table';
    }

    public function up(Schema $schema): void
    {
          $this->addSql('CREATE TABLE items (item_id CHAR(250) NOT NULL, product_name VARCHAR(250) NOT NULL,  quantity INT(20) NOT NULL,price FLOAT(20) NOT NULL,  PRIMARY KEY(item_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
    }

    public function down(Schema $schema): void
    {

        $this->addSql('DROP TABLE items');
    }
}
