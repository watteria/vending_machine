<?php

namespace App\Context\Customers\Event;

use App\SharedKernel\Domain\Bus\Query\Query;

abstract class CustomersQuery implements Query
{
    protected function boundedContext(): string
    {
        return 'customers';
    }
}