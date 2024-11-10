<?php

namespace App\Context\Items\Event;

use App\SharedKernel\Domain\Bus\Event\DomainEvent;

abstract class ItemsDomainEvent extends DomainEvent
{


    public function __construct(
        string $id,
        private readonly string $item_id,
        private readonly string $product_name,
        private readonly int $quantity,
        private readonly float $price,
        string $eventId = null,
        string $occurredOn = null
    ) {
        parent::__construct($id, $eventId, $occurredOn);
    }



    public function toPrimitives(): array
    {
        return [
            'item_id' => $this->item_id,
            'product_name' => $this->product_name,
            'quantity' => $this->quantity,
            'price' => $this->price,
        ];
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
    protected function boundedContext(): string
    {
        return 'items';
    }
}
