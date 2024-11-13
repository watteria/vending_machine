<?php
namespace App\Context\Customers\Customer\Infrastructure\Persistence\Doctrine\mapping;

use App\Context\Customers\Customer\Domain\ValueObject\CustomerInsertedMoney;
use Doctrine\ODM\MongoDB\Types\Type;

class CustomerInsertedMoneyType extends Type{
    const NAME = 'customer_inserted_money';


    public function convertToPHPValue($value)
    {
        $decodedValue = json_decode($value, true);
        return new CustomerInsertedMoney(json_encode($decodedValue));
    }

    public function convertToDatabaseValue($value)
    {
    return $value instanceof CustomerInsertedMoney ? $value->value() : json_encode($value);
    }

    public function getName()
    {
    return self::NAME;
    }

    public function closureToPHP(): string
    {
        return '$return = new \App\Context\Customers\Customer\Domain\ValueObject\CustomerInsertedMoney($value);';
    }
}
