<?php

namespace App\Context\Items\Event;

use App\Context\Items\Item\Domain\ValueObject\ItemId;
use App\Context\Items\Item\Domain\ValueObject\ItemPrice;
use App\Context\Items\Item\Domain\ValueObject\ItemProductName;
use App\Context\Items\Item\Domain\ValueObject\ItemQuantity;
use App\SharedKernel\Domain\Bus\Command\Command;

abstract class ItemsCommand implements Command
{

    public function __construct(private readonly ItemId $item_id, private readonly ItemProductName $product_name, private readonly ItemQuantity $quantity, private readonly ItemPrice $price)
    {
    }
    public function id(): ItemId
    {
        return $this->item_id;
    }
    public function item_id(): ItemId
    {
        return $this->item_id;
    }

    public function product_name(): ItemProductName
    {
        return $this->product_name;
    }

    public function quantity(): ItemQuantity
    {
        return $this->quantity;
    }
    public function price(): ItemPrice
    {
        return $this->price;
    }


}
