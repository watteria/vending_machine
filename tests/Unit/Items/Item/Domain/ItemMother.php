<?php

namespace App\Tests\Unit\Items\Item\Domain;

use App\Context\Items\Item\Domain\Item;
use App\Tests\Unit\SharedKernel\Domain\Mothers\FloatMother;
use App\Tests\Unit\SharedKernel\Domain\Mothers\IntMother;
use App\Tests\Unit\SharedKernel\Domain\Mothers\StringMother;
use App\Tests\Unit\SharedKernel\Domain\Mothers\UuidMother;

class ItemMother
{

    public static function create(
        ?string $item_id = null,
        ?string $product_name = null,
        ?int $quantity = null,
        ?float $price = null
    ): Item {
        return new Item(
            $item_id ?? UuidMother::create(),
            $product_name ?? StringMother::create(10),
            $quantity ?? IntMother::create(2),
            $price ?? FloatMother::create(2)
        );
    }

    public static function default(): Item
    {
        return self::create();
    }
}