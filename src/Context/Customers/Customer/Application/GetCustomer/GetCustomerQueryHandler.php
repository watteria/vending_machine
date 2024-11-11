<?php

namespace App\Context\Customers\Customer\Application\GetCustomer;

use App\Context\Customers\Customer\Domain\Customer;
use App\SharedKernel\Domain\Aggregate\Exception\AggregateNotFoundException;
use App\SharedKernel\Domain\Bus\Query\QueryHandler;
use App\SharedKernel\Domain\Bus\Query\Response;

class GetCustomerQueryHandler implements QueryHandler
{
    public function __construct(private readonly GetCustomerUseCase $useCase)
    {
    }

    public function __invoke(GetCustomerQuery $query): Response
    {
        $customer = $this->useCase->__invoke( $query->customer_id());
        if (null === $customer) {
            throw AggregateNotFoundException::fromAggregate(Customer::class);
        }

        return new GetCustomerResponse($customer);
    }
}
