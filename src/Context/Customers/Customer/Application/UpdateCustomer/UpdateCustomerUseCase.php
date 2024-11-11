<?php

namespace App\Context\Customers\Customer\Application\UpdateCustomer;

use App\Context\Customers\Customer\Domain\Repository\CustomerRepository;
use App\Context\Customers\Customer\Domain\Customer;
use App\SharedKernel\Domain\Bus\Event\EventBus;

class UpdateCustomerUseCase
{
    public function __construct(
        private readonly CustomerRepository $repository,
        private readonly EventBus $eventBus
    ) {
    }

    public function __invoke(string $customer_id, string $id_product,string $inserted_money,string $status,string $remaining_machine_coins): void
    {
        $customer = Customer::update($customer_id, $id_product,$inserted_money,$status,$remaining_machine_coins);

        $this->repository->save($customer);

        $this->eventBus->publish(...$customer->pullDomainEvents());
    }
}
