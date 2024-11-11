<?php

namespace App\Context\Customers\Customer\Application\ResetCustomer;

use App\Context\Customers\Event\CustomersCommand;

class ResetCustomerCommand extends CustomersCommand
{

    protected function messageName(): string
    {
        return 'customer.customer_reset';
    }
}
