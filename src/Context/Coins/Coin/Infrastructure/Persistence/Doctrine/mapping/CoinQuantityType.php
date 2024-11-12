<?php
namespace App\Context\Coins\Coin\Infrastructure\Persistence\Doctrine\mapping;

use App\Context\Coins\Coin\Domain\ValueObject\CoinQuantity;
use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\Type;

class CoinQuantityType extends Type{
    const NAME = 'coin_quantity';

    public function getSQLDeclaration(array $column, AbstractPlatform $platform)
    {
        return $platform->getIntegerTypeDeclarationSQL($column);
    }
    public function convertToPHPValue($value, AbstractPlatform $platform)
    {
    return new CoinQuantity($value);
    }

    public function convertToDatabaseValue($value, AbstractPlatform $platform)
    {
    return $value instanceof CoinQuantity ? $value->value() : $value;
    }

    public function getName()
    {
    return self::NAME;
    }
}
