<?php

namespace App\Context\Coins\Coin\Application\AllCoins;

use App\Context\Coins\Event\CoinsQuery;

class AllCoinsQuery extends CoinsQuery
{

    public static function create(): self
    {

        return new self();
    }


    protected function messageName(): string
    {
        return 'coins.get_all';
    }
}
