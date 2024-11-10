<?php

namespace App\Context\Coins\Event;

use App\SharedKernel\Domain\Bus\Query\Query;

abstract class CoinsQuery implements Query
{
    protected function boundedContext(): string
    {
        return 'coins';
    }
}
