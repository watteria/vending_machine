<?php
namespace App\Context\Coins\Coin\Infrastructure\Persistence\Doctrine\mapping;

use App\Context\Coins\Coin\Domain\ValueObject\CoinValidForChange;
use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\Type;

class CoinValidForChangeType extends Type{
    const NAME = 'coin_valid_for_change';

    public function getSQLDeclaration(array $column, AbstractPlatform $platform)
    {
        return $platform->getBooleanTypeDeclarationSQL($column);
    }
    public function convertToPHPValue($value, AbstractPlatform $platform)
    {
        $booleanValue = (bool) $value;
            return new CoinValidForChange($booleanValue);
    }

    public function convertToDatabaseValue($value, AbstractPlatform $platform)
    {
    return  $value instanceof CoinValidForChange ? (int)$value->value() :  (int)$value;
    }

    public function getName()
    {
    return self::NAME;
    }
}
