<?php
namespace App\Context\Items\Item\Infrastructure\Persistence\Doctrine\mapping;

use App\Context\Items\Item\Domain\ValueObject\ItemPrice;
use Doctrine\ODM\MongoDB\Types\Type;

class ItemPriceType extends Type{
    const NAME = 'item_price';



    public function convertToPHPValue($value): ItemPrice
    {
    return new ItemPrice($value);
    }

    public function convertToDatabaseValue($value)
    {
    return $value instanceof ItemPrice ? $value->value() : $value;
    }

    public function getName(): string
    {
    return self::NAME;
    }

    public function closureToPHP(): string
    {
        return '$return = new \App\Context\Items\Item\Domain\ValueObject\ItemPrice($value);';
    }
}
