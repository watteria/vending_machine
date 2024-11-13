<?php
namespace App\Context\Coins\Coin\Infrastructure\Persistence\Doctrine\mapping;

use App\Context\Coins\Coin\Domain\ValueObject\CoinId;
use Doctrine\ODM\MongoDB\Types\Type;

class CoinIdType extends Type{
    const NAME = 'coin_id';


    public function convertToPHPValue($value)
    {
    return new CoinId($value);
    }

    public function convertToDatabaseValue($value)
    {
    return $value instanceof CoinId ? $value->value() : $value;
    }

    public function getName()
    {
    return self::NAME;
    }
    public function closureToPHP(): string
    {
        return '$return = new \App\Context\Coins\Coin\Domain\ValueObject\CoinId($value);';
    }
}
