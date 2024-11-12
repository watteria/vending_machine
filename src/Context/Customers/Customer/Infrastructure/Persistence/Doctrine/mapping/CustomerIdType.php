<?php
namespace App\Context\Customers\Customer\Infrastructure\Persistence\Doctrine\mapping;

use App\Context\Customers\Customer\Domain\ValueObject\CustomerId;
use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\Type;

class CustomerIdType extends Type{
    const NAME = 'customer_id';

    public function getSQLDeclaration(array $column, AbstractPlatform $platform)
    {
        return $platform->getStringTypeDeclarationSQL($column);
    }

    public function convertToPHPValue($value, AbstractPlatform $platform)
    {
    return new CustomerId($value);
    }

    public function convertToDatabaseValue($value, AbstractPlatform $platform)
    {
    return $value instanceof CustomerId ? $value->value() : $value;
    }

    public function getName()
    {
    return self::NAME;
    }
}
