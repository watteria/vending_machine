<?php

namespace App\Context\Coins\Coin\Domain;

use App\Context\Coins\Coin\Domain\Event\CoinWasUpdated;
use App\Context\Coins\Coin\Domain\ValueObject\CoinId;
use App\Context\Coins\Coin\Domain\ValueObject\CoinQuantity;
use App\Context\Coins\Coin\Domain\ValueObject\CoinValidForChange;
use App\Context\Coins\Coin\Domain\ValueObject\CoinValue;
use App\SharedKernel\Domain\Aggregate\AggregateRoot;

class Coin extends AggregateRoot
{
    public function __construct(private readonly  CoinId $coin_id,  private readonly CoinQuantity $quantity, private readonly CoinValue $coin_value, private readonly CoinValidForChange $valid_for_change)
    {
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

    public static function update(CoinId $coin_id,CoinQuantity $quantity,CoinValue $coin_value, CoinValidForChange $valid_for_change): self
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
