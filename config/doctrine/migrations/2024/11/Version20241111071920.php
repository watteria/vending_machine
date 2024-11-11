<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20241111071920 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Customers table';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('CREATE TABLE customers (customer_id CHAR(250) NOT NULL, id_product CHAR(250) ,  inserted_money TEXT, status VARCHAR(50), PRIMARY KEY(customer_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('DROP TABLE customers');
    }
}
