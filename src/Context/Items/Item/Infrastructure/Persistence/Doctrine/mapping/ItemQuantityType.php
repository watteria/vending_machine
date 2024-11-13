<?php
namespace App\Context\Items\Item\Infrastructure\Persistence\Doctrine\mapping;

use App\Context\Items\Item\Domain\ValueObject\ItemQuantity;
use Doctrine\ODM\MongoDB\Types\Type;

class ItemQuantityType extends Type{
    const NAME = 'item_quantity';


    public function convertToPHPValue($value): ItemQuantity
    {
    return new ItemQuantity($value);
    }

    public function convertToDatabaseValue($value)
    {
    return $value instanceof ItemQuantity ? $value->value() : $value;
    }

    public function getName(): string
    {
    return self::NAME;
    }

    public function closureToPHP(): string
    {
        return '$return = new \App\Context\Items\Item\Domain\ValueObject\ItemQuantity($value);';
    }
}
