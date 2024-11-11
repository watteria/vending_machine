<?php

namespace App\Context\Customers\Customer\Application\GetCustomer;

use App\Context\Customers\Event\CustomersQuery;

class GetCustomerQuery extends CustomersQuery
{

    public function __construct(private readonly string $customer_id) {}


    public function customer_id(): string
    {
        return $this->customer_id;
    }




    protected function messageName(): string
    {
        return 'customer.get';
    }
}
