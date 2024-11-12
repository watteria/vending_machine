<?php

namespace App\Tests\Unit\Customers\Customer\Application\UpdateCustomer;

use App\Context\Customers\Customer\Application\UpdateCustomer\UpdateCustomerUseCase;
use App\Context\Customers\Customer\Domain\Event\CustomerWasUpdated;
use App\Context\Customers\Customer\Domain\Repository\CustomerRepository;
use App\Context\Customers\Customer\Domain\Customer;
use App\Context\Customers\Customer\Domain\ValueObject\CustomerId;
use App\Context\Customers\Customer\Domain\ValueObject\CustomerInsertedMoney;
use App\Context\Customers\Customer\Domain\ValueObject\CustomerStatus;
use App\Context\Items\Item\Domain\ValueObject\ItemId;
use App\SharedKernel\Domain\Bus\Event\EventBus;
use App\Tests\Unit\Coins\Coin\Domain\CoinMother;
use App\Tests\Unit\Items\Item\Domain\ItemMother;
use App\Tests\Unit\SharedKernel\Domain\Mothers\IntMother;
use App\Tests\Unit\SharedKernel\Domain\Mothers\UuidMother;
use PHPUnit\Framework\TestCase;

class UpdateCustomerUseCaseTest extends TestCase
{
    public function test_it_updates_customer_and_publishes_event(): void
    {
        $repository = $this->createMock(CustomerRepository::class);
        $eventBus = $this->createMock(EventBus::class);

        $customerId = CustomerId::random();
        $idProduct = ItemId::random();
        $insertedMoney = CustomerInsertedMoney::randomCoins(3);
        $status = new CustomerStatus('IN_PROCESS');
        $remainingMachineCoins =  CoinMother::createArray(3);

        $updatedCustomer = Customer::update($customerId, $idProduct, $insertedMoney, $status, $remainingMachineCoins);

        $repository
            ->expects(self::once())
            ->method('save')
            ->with($this->callback(function (Customer $savedCustomer) use ($updatedCustomer) {
                return $savedCustomer->id()->value() === $updatedCustomer->id()->value() &&
                    $savedCustomer->status()->value() === $updatedCustomer->status()->value() &&
                    $savedCustomer->inserted_money()->value() === $updatedCustomer->inserted_money()->value();
            }));

        $eventBus
            ->expects(self::once())
            ->method('publish')
            ->with($this->callback(function ($event) use ($updatedCustomer) {
                return $event instanceof CustomerWasUpdated &&
                    $event->aggregateId() === $updatedCustomer->id()->value() &&
                    $event->customer_id()->value() === $updatedCustomer->id()->value() &&
                    $event->id_product()->value() === $updatedCustomer->id_product()->value() &&
                    $event->inserted_money()->value() === $updatedCustomer->inserted_money()->value() &&
                    $event->status()->value() === $updatedCustomer->status()->value() &&
                    $event->remaining_machine_coins() === $updatedCustomer->remaining_machine_coins();
            }));

        $useCase = new UpdateCustomerUseCase($repository, $eventBus);
        $useCase->__invoke($customerId, $idProduct, $insertedMoney, $status, $remainingMachineCoins);
    }
}
