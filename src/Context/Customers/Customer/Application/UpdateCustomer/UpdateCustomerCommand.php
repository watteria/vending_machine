<?php

namespace App\Context\Customers\Customer\Application\UpdateCustomer;

use App\Context\Customers\Event\CustomersCommand;

class UpdateCustomerCommand extends CustomersCommand
{

    protected function messageName(): string
    {
        return 'customer.customer_updated';
    }
}
