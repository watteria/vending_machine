<?php
namespace App\Context\Items\Item\Infrastructure\Persistence\Doctrine\mapping;

use App\Context\Items\Item\Domain\ValueObject\ItemId;
use Doctrine\ODM\MongoDB\Types\Type;

class ItemIdType extends Type{
    const NAME = 'item_id';


    public function convertToPHPValue($value): ItemId
    {
    return new ItemId($value);
    }

    public function convertToDatabaseValue($value)
    {
    return $value instanceof ItemId ? $value->value() : $value;
    }

    public function getName()
    {
    return self::NAME;
    }
    public function closureToPHP(): string
    {
        return '$return = new \App\Context\Items\Item\Domain\ValueObject\ItemId($value);';
    }
}
