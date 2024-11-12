<?php

namespace App\Context\Coins\Event;

use App\Context\Coins\Coin\Domain\ValueObject\CoinId;
use App\Context\Coins\Coin\Domain\ValueObject\CoinQuantity;
use App\Context\Coins\Coin\Domain\ValueObject\CoinValidForChange;
use App\Context\Coins\Coin\Domain\ValueObject\CoinValue;
use App\SharedKernel\Domain\Bus\Event\DomainEvent;

abstract class CoinsDomainEvent extends DomainEvent
{

    public function __construct(
        string $id,
        private readonly CoinId  $coin_id,
        private readonly CoinQuantity  $quantity,
        private readonly CoinValue  $coin_value,
        private readonly CoinValidForChange  $valid_for_change,
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

    public function id(): CoinId
    {
        return $this->coin_id;
    }
    public function coin_id(): CoinId
    {
        return $this->coin_id;
    }


    public function quantity(): CoinQuantity
    {
        return $this->quantity;
    }


    public function coin_value(): CoinValue
    {
        return $this->coin_value;
    }



    public function valid_for_change(): CoinValidForChange
    {
        return $this->valid_for_change;
    }


}
