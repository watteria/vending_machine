<?php

namespace App\Context\Coins\Coin\Application\TotalAmount;

use App\SharedKernel\Domain\Bus\Query\Response;

class TotalAmountResponse implements Response
{
    public function __construct(private readonly array $coins)
    {
    }

    public function result(): array
    {
        $result_final=array();
        foreach ($this->coins as $coin){
            $result_final[]=[
                'coin_id' => $coin->coin_id()->value(),
                'quantity' => $coin->quantity()->value(),
                'coin_value' => $coin->coin_value()->value(),
                'valid_for_change'=>(bool)$coin->valid_for_change()
            ];
        }
        return $result_final;
    }

}
