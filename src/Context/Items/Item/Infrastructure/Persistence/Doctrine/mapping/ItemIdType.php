<?php
namespace App\Context\Items\Item\Infrastructure\Persistence\Doctrine\mapping;

use App\Context\Items\Item\Domain\ValueObject\ItemId;
use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\Type;

class ItemIdType extends Type{
    const NAME = 'item_id';

    public function getSQLDeclaration(array $column, AbstractPlatform $platform)
    {
        return $platform->getStringTypeDeclarationSQL($column);
    }

    public function convertToPHPValue($value, AbstractPlatform $platform)
    {
    return new ItemId($value);
    }

    public function convertToDatabaseValue($value, AbstractPlatform $platform)
    {
    return $value instanceof ItemId ? $value->value() : $value;
    }

    public function getName()
    {
    return self::NAME;
    }
}
