<?php

namespace App\Tests\Unit\Customers\Customer\Application\CreateCustomer;

use App\Context\Customers\Customer\Application\CreateCustomer\CreateCustomerUseCase;
use App\Context\Customers\Customer\Domain\Event\CustomerWasCreated;
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

class CreateCustomerUseCaseTest extends TestCase
{
    public function test_it_creates_customer_and_publishes_event(): void
    {
        $repository = $this->createMock(CustomerRepository::class);
        $eventBus = $this->createMock(EventBus::class);

        $customerId = CustomerId::random();
        $idProduct = ItemId::random();
        $insertedMoney =  CustomerInsertedMoney::randomCoins(3);
        $status = new CustomerStatus('IN_PROCESS');
        $remainingMachineCoins = CoinMother::createArray(3);

        $createdCustomer = Customer::create($customerId, $idProduct, $insertedMoney, $status, $remainingMachineCoins);

        $repository
            ->expects(self::once())
            ->method('save')
            ->with($this->callback(function (Customer $savedCustomer) use ($createdCustomer) {
                return $savedCustomer->id()->value()  === $createdCustomer->id()->value()  &&
                    $savedCustomer->status()->value()  === $createdCustomer->status()->value()  &&
                    $savedCustomer->inserted_money() === $createdCustomer->inserted_money();
            }));

        $eventBus
            ->expects(self::once())
            ->method('publish')
            ->with($this->callback(function ($event) use ($createdCustomer) {
                return $event instanceof CustomerWasCreated &&
                    $event->aggregateId() === $createdCustomer->id()->value()  &&
                    $event->customer_id()->value()  === $createdCustomer->id()->value()  &&
                    $event->id_product()->value()  === $createdCustomer->id_product()->value()  &&
                    $event->inserted_money()->value()  === $createdCustomer->inserted_money()->value()  &&
                    $event->status()->value()  === $createdCustomer->status()->value()  &&
                    $event->remaining_machine_coins() === $createdCustomer->remaining_machine_coins();
            }));

        $useCase = new CreateCustomerUseCase($repository, $eventBus);
        $useCase->__invoke($customerId, $idProduct, $insertedMoney, $status, $remainingMachineCoins);
    }
}
