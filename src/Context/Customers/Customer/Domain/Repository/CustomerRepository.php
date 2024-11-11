<?php

namespace App\Context\Customers\Customer\Domain\Repository;

use App\Context\Customers\Customer\Domain\Customer;

interface CustomerRepository
{


    public function searchAll(): array;
    public function search($customer_id): ?Customer;

    public function save(Customer $customer): void;
    public function delete(Customer $customer): void;
}
