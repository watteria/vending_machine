<?php
namespace App\Context\Customers\Customer\Infrastructure\Persistence\Doctrine\mapping;

use App\Context\Customers\Customer\Domain\ValueObject\CustomerInsertedMoney;
use App\Context\Customers\Customer\Domain\ValueObject\CustomerStatus;
use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\Type;

class CustomerInsertedMoneyType extends Type{
    const NAME = 'customer_inserted_money';

    public function getSQLDeclaration(array $column, AbstractPlatform $platform)
    {
        return $platform->getJsonTypeDeclarationSQL($column);
    }

    public function convertToPHPValue($value, AbstractPlatform $platform)
    {
        $decodedValue = json_decode($value, true);
        return new CustomerInsertedMoney(json_encode($decodedValue));
    }

    public function convertToDatabaseValue($value, AbstractPlatform $platform)
    {
    return $value instanceof CustomerInsertedMoney ? $value->value() : json_encode($value);
    }

    public function getName()
    {
    return self::NAME;
    }
}
