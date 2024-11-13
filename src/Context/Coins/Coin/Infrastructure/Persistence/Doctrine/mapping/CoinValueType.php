<?php
namespace App\Context\Coins\Coin\Infrastructure\Persistence\Doctrine\mapping;;

use App\Context\Coins\Coin\Domain\ValueObject\CoinValue;
use Doctrine\ODM\MongoDB\Types\Type;

class CoinValueType extends Type{
    const NAME = 'coin_value';



    public function convertToPHPValue($value) : CoinValue
    {
    return new CoinValue($value);
    }

    public function convertToDatabaseValue($value)
    {
    return $value instanceof CoinValue ? $value->value() : $value;
    }

    public function getName(): string
    {
    return self::NAME;
    }

    public function closureToPHP(): string
    {
        return '$return = new \App\Context\Coins\Coin\Domain\ValueObject\CoinValue($value);';
    }
}
