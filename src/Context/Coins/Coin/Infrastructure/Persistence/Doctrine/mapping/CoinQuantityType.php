<?php
namespace App\Context\Coins\Coin\Infrastructure\Persistence\Doctrine\mapping;

use App\Context\Coins\Coin\Domain\ValueObject\CoinQuantity;
use Doctrine\ODM\MongoDB\Types\Type;

class CoinQuantityType extends Type{
    const NAME = 'coin_quantity';


    public function convertToPHPValue($value)
    {
    return new CoinQuantity($value);
    }

    public function convertToDatabaseValue($value)
    {
    return $value instanceof CoinQuantity ? $value->value() : $value;
    }

    public function getName()
    {
    return self::NAME;
    }
    public function closureToPHP(): string
    {
        return '$return = new \App\Context\Coins\Coin\Domain\ValueObject\CoinQuantity($value);';
    }
}
