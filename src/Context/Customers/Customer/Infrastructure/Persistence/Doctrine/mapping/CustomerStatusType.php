<?php
namespace App\Context\Customers\Customer\Infrastructure\Persistence\Doctrine\mapping;

use App\Context\Customers\Customer\Domain\ValueObject\CustomerStatus;
use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\Type;

class CustomerStatusType extends Type{
    const NAME = 'customer_status';

    public function getSQLDeclaration(array $column, AbstractPlatform $platform)
    {
        return $platform->getStringTypeDeclarationSQL($column);
    }

    public function convertToPHPValue($value, AbstractPlatform $platform)
    {
    return new CustomerStatus($value);
    }

    public function convertToDatabaseValue($value, AbstractPlatform $platform)
    {
    return $value instanceof CustomerStatus ? $value->value() : $value;
    }

    public function getName()
    {
    return self::NAME;
    }
}
