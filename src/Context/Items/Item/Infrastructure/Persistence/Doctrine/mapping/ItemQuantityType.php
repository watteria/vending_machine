<?php
namespace App\Context\Items\Item\Infrastructure\Persistence\Doctrine\mapping;

use App\Context\Items\Item\Domain\ValueObject\ItemQuantity;
use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\Type;

class ItemQuantityType extends Type{
    const NAME = 'item_quantity';

    public function getSQLDeclaration(array $column, AbstractPlatform $platform)
    {
        return $platform->getIntegerTypeDeclarationSQL($column);
    }
    public function convertToPHPValue($value, AbstractPlatform $platform)
    {
    return new ItemQuantity($value);
    }

    public function convertToDatabaseValue($value, AbstractPlatform $platform)
    {
    return $value instanceof ItemQuantity ? $value->value() : $value;
    }

    public function getName()
    {
    return self::NAME;
    }
}
