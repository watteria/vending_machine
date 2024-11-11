<?php

namespace App\Context\Customers\Customer\Application\CreateCustomer;

use App\Context\Customers\Event\CustomersCommand;

class CreateCustomerCommand extends CustomersCommand
{

    protected function messageName(): string
    {
        return 'customer.customer_created';
    }
}