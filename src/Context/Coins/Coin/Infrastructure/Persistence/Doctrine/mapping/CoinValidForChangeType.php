<?php
namespace App\Context\Coins\Coin\Infrastructure\Persistence\Doctrine\mapping;

use App\Context\Coins\Coin\Domain\ValueObject\CoinValidForChange;
use Doctrine\ODM\MongoDB\Types\Type;

class CoinValidForChangeType extends Type{
    const NAME = 'coin_valid_for_change';


    public function convertToPHPValue($value)
    {
        $booleanValue = (bool) $value;
            return new CoinValidForChange($booleanValue);
    }

    public function convertToDatabaseValue($value)
    {
    return  $value instanceof CoinValidForChange ? $value->value() :  $value;
    }

    public function getName()
    {
    return self::NAME;
    }

    public function closureToPHP(): string
    {
        return '$return = new \App\Context\Coins\Coin\Domain\ValueObject\CoinValidForChange($value);';
    }
}
