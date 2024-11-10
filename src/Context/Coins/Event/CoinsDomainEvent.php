<?php

namespace App\Context\Coins\Event;

use App\SharedKernel\Domain\Bus\Event\DomainEvent;

abstract class CoinsDomainEvent extends DomainEvent
{

    public function __construct(
        string $id,
        private readonly string $coin_id,
        private readonly int $quantity,
        private readonly float $coin_value,
        private readonly int $valid_for_change,
        string $eventId = null,
        string $occurredOn = null
    ) {
        parent::__construct($id, $eventId, $occurredOn);
    }



    public function toPrimitives(): array
    {
        return [
            'coin_id' => $this->coin_id,
            'quantity' => $this->quantity,
            'coin_value' => $this->coin_value,
            'valid_for_change' => $this->valid_for_change,
        ];
    }

    public function id(): string
    {
        return $this->coin_id;
    }
    public function coin_id(): string
    {
        return $this->coin_id;
    }


    public function quantity(): int
    {
        return $this->quantity;
    }


    public function coin_value(): float
    {
        return $this->coin_value;
    }



    public function valid_for_change(): int
    {
        return $this->valid_for_change;
    }

    protected function boundedContext(): string
    {
        return 'coins';
    }
}
