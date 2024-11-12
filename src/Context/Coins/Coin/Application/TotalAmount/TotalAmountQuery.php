<?php

namespace App\Context\Coins\Coin\Application\TotalAmount;

use App\Context\Coins\Event\CoinsQuery;

class TotalAmountQuery extends CoinsQuery
{

    public static function create(): self
    {

        return new self();
    }


}
