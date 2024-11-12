<?php

namespace App\Context\Items\Item\Domain;

use App\Context\Items\Item\Domain\Event\ItemWasCreated;
use App\Context\Items\Item\Domain\Event\ItemWasDeleted;
use App\Context\Items\Item\Domain\Event\ItemWasUpdated;
use App\Context\Items\Item\Domain\ValueObject\ItemId;
use App\Context\Items\Item\Domain\ValueObject\ItemPrice;
use App\Context\Items\Item\Domain\ValueObject\ItemProductName;
use App\Context\Items\Item\Domain\ValueObject\ItemQuantity;
use App\SharedKernel\Domain\Aggregate\AggregateRoot;

class Item extends AggregateRoot
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


    public static function create(ItemId $id, ItemProductName $product_name,ItemQuantity $quantity,ItemPrice $price): self
    {
        $item = new self($id, $product_name,$quantity,$price);

        $item->record(new ItemWasCreated(
            $item->id(),
            $item->item_id(),
            $item->product_name(),
            $item->quantity(),
            $item->price()
        ));

        return $item;
    }

    public static function update(ItemId $id, ItemProductName $product_name,ItemQuantity $quantity,ItemPrice $price): self
    {
        $item = new self($id, $product_name,$quantity,$price);

        $item->record(new ItemWasUpdated(
            $item->id(),
            $item->item_id(),
            $item->product_name(),
            $item->quantity(),
            $item->price()
        ));

        return $item;
    }

    public static function delete(ItemId $id, ItemProductName $product_name,ItemQuantity $quantity,ItemPrice $price): self
    {
        $item = new self($id, $product_name,$quantity,$price);

        $item->record(new ItemWasDeleted(
            $item->id(),
            $item->item_id(),
            $item->product_name(),
            $item->quantity(),
            $item->price()
        ));

        return $item;
    }


}
