<?php

namespace App\Context\Coins\Coin\Domain;

use App\Context\Coins\Coin\Domain\Event\CoinWasUpdated;
use App\SharedKernel\Domain\Aggregate\AggregateRoot;

class Coin extends AggregateRoot
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

    public static function update(string $coin_id,int $quantity,float $coin_value, int $valid_for_change): self
    {
        $coin = new self($coin_id, $quantity,$coin_value,$valid_for_change);

        $coin->record(new CoinWasUpdated(
            $coin->id(),
            $coin->coin_id(),
            $coin->quantity(),
            $coin->coin_value(),
            $coin->valid_for_change()
        ));

        return $coin;
    }




}
