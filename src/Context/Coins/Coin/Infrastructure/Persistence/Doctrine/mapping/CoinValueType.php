<?php
namespace App\Context\Coins\Coin\Infrastructure\Persistence\Doctrine\mapping;;

use App\Context\Coins\Coin\Domain\ValueObject\CoinValue;
use App\Context\Items\Item\Domain\ValueObject\ItemPrice;
use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\Type;

class CoinValueType extends Type{
    const NAME = 'coin_value';

    public function getSQLDeclaration(array $column, AbstractPlatform $platform)
    {
        return $platform->getFloatDeclarationSQL($column);
    }

    public function convertToPHPValue($value, AbstractPlatform $platform)
    {
    return new CoinValue($value);
    }

    public function convertToDatabaseValue($value, AbstractPlatform $platform)
    {
    return $value instanceof CoinValue ? $value->value() : $value;
    }

    public function getName()
    {
    return self::NAME;
    }
}
