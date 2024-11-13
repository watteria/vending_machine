<?php
namespace App\Context\Customers\Customer\Infrastructure\Persistence\Doctrine\mapping;

use App\Context\Customers\Customer\Domain\ValueObject\CustomerId;
use Doctrine\ODM\MongoDB\Types\Type;

class CustomerIdType extends Type{
    const NAME = 'customer_id';


    public function convertToPHPValue($value)
    {
    return new CustomerId($value);
    }

    public function convertToDatabaseValue($value)
    {
    return $value instanceof CustomerId ? $value->value() : $value;
    }

    public function getName()
    {
    return self::NAME;
    }

    public function closureToPHP(): string
    {
        return '$return = new \App\Context\Customers\Customer\Domain\ValueObject\CustomerId($value);';
    }
}
