<?php

namespace App\Tests\Unit\Customers\Customer\Application\GetCustomer;

use App\Context\Customers\Customer\Application\GetCustomer\GetCustomerUseCase;
use App\Context\Customers\Customer\Domain\Repository\CustomerRepository;
use App\Tests\Unit\Customers\Customer\Domain\CustomerMother;
use App\Tests\Unit\SharedKernel\UnitTestCase;

class GetCustomerUseCaseTest extends UnitTestCase
{
    public function test_it_returns_customer_when_found(): void
    {
        $repository = $this->createMock(CustomerRepository::class);
        $customer = CustomerMother::default();
        $customerId = $customer->customer_id();

        $repository
            ->expects(self::once())
            ->method('search')
            ->with($customerId)
            ->willReturn($customer);

        $useCase = new GetCustomerUseCase($repository);
        $result = $useCase->__invoke($customerId);

        $this->assertEquals($customer, $result, 'The retrieved customer should match the expected customer.');
    }

    public function test_it_returns_null_when_item_not_found(): void
    {
        $repository = $this->createMock(CustomerRepository::class);
        $customerId = 'non-existent-id';

        $repository
            ->expects(self::once())
            ->method('search')
            ->with($customerId)
            ->willReturn(null);

        $useCase = new GetCustomerUseCase($repository);
        $result = $useCase->__invoke($customerId);

        $this->assertNull($result, 'The result should be null when the item is not found.');
    }
}
