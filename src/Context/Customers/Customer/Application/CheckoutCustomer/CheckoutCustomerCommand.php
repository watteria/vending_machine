<?php

namespace App\Context\Customers\Customer\Application\CheckoutCustomer;

use App\Context\Customers\Event\CustomersCommand;

class CheckoutCustomerCommand extends CustomersCommand
{
    protected function messageName(): string
    {
        return 'customer.customer_checkout';
    }
}
