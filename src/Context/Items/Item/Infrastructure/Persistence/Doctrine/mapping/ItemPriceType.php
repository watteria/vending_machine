<?php
namespace App\Context\Items\Item\Infrastructure\Persistence\Doctrine\mapping;

use App\Context\Items\Item\Domain\ValueObject\ItemPrice;
use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\Type;

class ItemPriceType extends Type{
    const NAME = 'item_price';

    public function getSQLDeclaration(array $column, AbstractPlatform $platform)
    {
        return $platform->getFloatDeclarationSQL($column);
    }

    public function convertToPHPValue($value, AbstractPlatform $platform)
    {
    return new ItemPrice($value);
    }

    public function convertToDatabaseValue($value, AbstractPlatform $platform)
    {
    return $value instanceof ItemPrice ? $value->value() : $value;
    }

    public function getName()
    {
    return self::NAME;
    }
}
