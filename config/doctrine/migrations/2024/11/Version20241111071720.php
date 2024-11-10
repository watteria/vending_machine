<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20241111071720 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Items table';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('INSERT INTO items (item_id,product_name,quantity,price) VALUES  ("306942d8-a757-4cc6-92d2-28c9b13b5dd8","Water",2,0.65), ("996a0505-d0ee-4eaf-ae30-acbd76781be2","Juice",2,1.00),("7ea894a0-9ee5-47a5-b85c-85fd05a8ddf9","Soda",1,1.50)');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('TRUNCATE TABLE items');
    }
}
