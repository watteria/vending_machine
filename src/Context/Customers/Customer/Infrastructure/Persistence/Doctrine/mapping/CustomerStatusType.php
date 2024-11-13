<?php
namespace App\Context\Customers\Customer\Infrastructure\Persistence\Doctrine\mapping;

use App\Context\Customers\Customer\Domain\ValueObject\CustomerStatus;
use Doctrine\ODM\MongoDB\Types\Type;

class CustomerStatusType extends Type{
    const NAME = 'customer_status';



    public function convertToPHPValue($value)
    {
    return new CustomerStatus($value);
    }

    public function convertToDatabaseValue($value)
    {
    return $value instanceof CustomerStatus ? $value->value() : $value;
    }

    public function getName()
    {
    return self::NAME;
    }
    public function closureToPHP(): string
    {
        return '$return = new \App\Context\Customers\Customer\Domain\ValueObject\CustomerStatus($value);';
    }
}
