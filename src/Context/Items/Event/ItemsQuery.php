<?php

namespace App\Context\Items\Event;

use App\SharedKernel\Domain\Bus\Query\Query;

abstract class ItemsQuery implements Query
{
    protected function boundedContext(): string
    {
        return 'items';
    }
}
