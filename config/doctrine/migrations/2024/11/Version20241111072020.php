<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20241111072020 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Customers table';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('ALTER TABLE coins MODIFY valid_for_change TINYINT(1) NOT NULL');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('ALTER TABLE coins MODIFY valid_for_change TINYINT(20) NOT NULL');

    }
}