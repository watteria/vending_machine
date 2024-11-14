<?php

namespace App\Context\Customers\Customer\Application\CheckoutCustomer;

use App\Context\Customers\Customer\Domain\Repository\CustomerRepository;
use App\Context\Customers\Customer\Domain\Customer;
use App\Context\Customers\Customer\Domain\ValueObject\CustomerId;
use App\Context\Customers\Customer\Domain\ValueObject\CustomerInsertedMoney;
use App\Context\Customers\Customer\Domain\ValueObject\CustomerStatus;
use App\Context\Items\Item\Domain\ValueObject\ItemId;
use App\SharedKernel\Domain\Bus\Event\EventBus;

class CheckoutCustomerUseCase
{
    public function __construct(
        private readonly CustomerRepository $repository,
        private readonly EventBus $eventBus
    ) {
    }

    /***
     * Checkout Customer
     *
     * @param CustomerId $customer_id
     * @param ItemId $id_product
     * @param CustomerInsertedMoney $inserted_money
     * @param CustomerStatus $status
     * @param array $remaining_machine_coins
     * @return void
     */

    public function __invoke(CustomerId $customer_id, ItemId $id_product,CustomerInsertedMoney $inserted_money,CustomerStatus $status,array $remaining_machine_coins): void
    {


        $customer = Customer::checkout($customer_id, $id_product,$inserted_money,$status,$remaining_machine_coins);

        $this->repository->save($customer);

        $this->eventBus->publish(...$customer->pullDomainEvents());
    }
}
