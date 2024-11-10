<?php

namespace App\Context\Items\Event;

use App\SharedKernel\Domain\Bus\Command\Command;

abstract class ItemsCommand implements Command
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


}
