<?php

namespace App\Context\Coins\Coin\Domain;

use App\Context\Coins\Coin\Domain\Event\CoinWasUpdated;
use App\Context\Coins\Coin\Domain\ValueObject\CoinId;
use App\Context\Coins\Coin\Domain\ValueObject\CoinQuantity;
use App\Context\Coins\Coin\Domain\ValueObject\CoinValidForChange;
use App\Context\Coins\Coin\Domain\ValueObject\CoinValue;
use App\SharedKernel\Domain\Aggregate\AggregateRoot;


#[ODM\Document(collection: "coins")]
class Coin extends AggregateRoot
{

    // ODM DEFINITION
    #[ODM\Id(strategy: 'NONE', type: 'string')]
    private string $_id;

    #[ODM\Field(type: 'coin_id')]
    private  CoinId $coin_id;

    #[ODM\Field(type: 'coin_quantity')]
    private  CoinQuantity $quantity;

    #[ODM\Field(type: 'coin_value')]
    private  CoinValue $coin_value;

    #[ODM\Field(type: 'coin_valid_for_change')]
    private  CoinValidForChange $valid_for_change;

    public function __construct(CoinId $coin_id,  CoinQuantity $quantity, CoinValue $coin_value, CoinValidForChange $valid_for_change)
    {
        $this->_id = $coin_id;
        $this->coin_id = $coin_id;
        $this->quantity = $quantity;
        $this->coin_value = $coin_value;
        $this->valid_for_change = $valid_for_change;
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

        // Register Domain Event
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
