<?php

namespace App\Context\Customers\Customer\Application\GetCustomer;

use App\Context\Customers\Customer\Domain\Customer;
use App\Context\Customers\Customer\Domain\Repository\CustomerRepository;

class GetCustomerUseCase
{
    public function __construct(private readonly CustomerRepository $repository)
    {
    }

    public function __invoke(string $customer_id): ? Customer
    {
        $customers = $this->repository->search( $customer_id);

        if (empty($customers)) {
            return null;
        }

        return $customers;
    }
}
