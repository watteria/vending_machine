<?php
namespace App\Context\Coins\Coin\Infrastructure\Persistence\Doctrine\mapping;

use App\Context\Coins\Coin\Domain\ValueObject\CoinId;
use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\Type;

class CoinIdType extends Type{
    const NAME = 'coin_id';

    public function getSQLDeclaration(array $column, AbstractPlatform $platform)
    {
        return $platform->getStringTypeDeclarationSQL($column);
    }

    public function convertToPHPValue($value, AbstractPlatform $platform)
    {
    return new CoinId($value);
    }

    public function convertToDatabaseValue($value, AbstractPlatform $platform)
    {
    return $value instanceof CoinId ? $value->value() : $value;
    }

    public function getName()
    {
    return self::NAME;
    }
}
