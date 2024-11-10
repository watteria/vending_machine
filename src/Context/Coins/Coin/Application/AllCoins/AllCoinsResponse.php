<?php

namespace App\Context\Coins\Coin\Application\AllCoins;

use App\SharedKernel\Domain\Bus\Query\Response;

class AllCoinsResponse implements Response
{
    public function __construct(private readonly array $coins)
    {
    }

    public function result(): array
    {
        $result_final=array();
        foreach ($this->coins as $coin){
            $result_final[]=[
                'coin_id' => $coin->coin_id(),
                'quantity' => $coin->quantity(),
                'coin_value' => $coin->coin_value(),
                'valid_for_change'=>$coin->valid_for_change()
            ];
        }
        return $result_final;
    }

}
