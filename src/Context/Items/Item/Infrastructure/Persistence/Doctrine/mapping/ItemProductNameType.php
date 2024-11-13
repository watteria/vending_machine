<?php
namespace App\Context\Items\Item\Infrastructure\Persistence\Doctrine\mapping;

use App\Context\Items\Item\Domain\ValueObject\ItemProductName;
use Doctrine\ODM\MongoDB\Types\Type;

class ItemProductNameType extends Type{
    const NAME = 'item_productname';


    public function convertToPHPValue($value): ItemProductName
    {
    return new ItemProductName($value);
    }

    public function convertToDatabaseValue($value)
    {
    return $value instanceof ItemProductName ? $value->value() : $value;
    }

    public function getName(): string
    {
    return self::NAME;
    }

    public function closureToPHP(): string
    {
        return '$return = new \App\Context\Items\Item\Domain\ValueObject\ItemProductName($value);';
    }
}
