<?php

namespace App\Context\Coins\Event;

use App\SharedKernel\Domain\Bus\Command\Command;

abstract class CoinsCommand implements Command
{


    public function __construct(private readonly string $coin_id,  private readonly int $quantity, private readonly float $coin_value, private readonly int $valid_for_change)
    {
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


}
