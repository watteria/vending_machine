<?php
namespace App\Context\Items\Item\Infrastructure\Persistence\Doctrine\mapping;

use App\Context\Items\Item\Domain\ValueObject\ItemProductName;
use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\Type;

class ItemProductNameType extends Type{
    const NAME = 'item_productname';

    public function getSQLDeclaration(array $column, AbstractPlatform $platform)
    {
        return $platform->getStringTypeDeclarationSQL($column);
    }

    public function convertToPHPValue($value, AbstractPlatform $platform)
    {
    return new ItemProductName($value);
    }

    public function convertToDatabaseValue($value, AbstractPlatform $platform)
    {
    return $value instanceof ItemProductName ? $value->value() : $value;
    }

    public function getName()
    {
    return self::NAME;
    }
}
