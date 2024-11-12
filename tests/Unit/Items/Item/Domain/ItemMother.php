<?php

namespace App\Tests\Unit\Items\Item\Domain;

use App\Context\Items\Item\Domain\Item;
use App\Context\Items\Item\Domain\ValueObject\ItemId;
use App\Context\Items\Item\Domain\ValueObject\ItemPrice;
use App\Context\Items\Item\Domain\ValueObject\ItemProductName;
use App\Context\Items\Item\Domain\ValueObject\ItemQuantity;
use App\Tests\Unit\SharedKernel\Domain\Mothers\FloatMother;
use App\Tests\Unit\SharedKernel\Domain\Mothers\IntMother;
use App\Tests\Unit\SharedKernel\Domain\Mothers\StringMother;
use App\Tests\Unit\SharedKernel\Domain\Mothers\UuidMother;

class ItemMother
{

    public static function create(
        ?ItemId $item_id = null,
        ?ItemProductName $product_name = null,
        ?ItemQuantity $quantity = null,
        ?ItemPrice $price = null
    ): Item {
        return new Item(
            $item_id ?? ItemId::random(),
            $product_name ?? ItemProductName::random(15),
            $quantity ?? ItemQuantity::random(0,10),
            $price ?? ItemPrice::random(2)
        );
    }

    public static function default(): Item
    {
        return self::create();
    }
}