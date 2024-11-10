<?php

namespace App\Context\Items\Item\Domain;

use App\Context\Items\Item\Domain\Event\ItemWasCreated;
use App\Context\Items\Item\Domain\Event\ItemWasDeleted;
use App\Context\Items\Item\Domain\Event\ItemWasUpdated;
use App\SharedKernel\Domain\Aggregate\AggregateRoot;

class Item extends AggregateRoot
{
    public function __construct(private readonly string $item_id, private readonly string $product_name, private readonly int $quantity, private readonly float $price)
    {
    }

    public function id(): string
    {
        return $this->item_id;
    }
    public function item_id(): string
    {
        return $this->item_id;
    }

    public function product_name(): string
    {
        return $this->product_name;
    }

    public function quantity(): int
    {
        return $this->quantity;
    }
    public function price(): float
    {
        return $this->price;
    }


    public static function create(string $id, string $product_name,int $quantity,float $price): self
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

    public static function update(string $id, string $product_name,int $quantity,float $price): self
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

    public static function delete(string $id, string $product_name,int $quantity,float $price): self
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
