<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20241111071820 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Coins table';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('CREATE TABLE coins (coin_id CHAR(250) NOT NULL, quantity INT(20) NOT NULL,  coin_value FLOAT(20) NOT NULL,valid_for_change INT(10), PRIMARY KEY(coin_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('INSERT INTO coins (coin_id,quantity,coin_value,valid_for_change) VALUES  ("3a990a45-bd5c-41a7-82e8-c9b21a581220",20,0.05,1), ("e48b2473-8562-432e-8305-4293be72056d",20,0.1,1),("3991b21f-3d6f-43bd-899c-a53bc5d2da13",20,0.25,1),("e25b1559-1fca-4e5b-b6be-c4e761a13064",12,1,0)');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('TRUNCATE TABLE coins');
    }
}
