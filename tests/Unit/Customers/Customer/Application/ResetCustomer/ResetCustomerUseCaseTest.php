<?php

namespace App\Tests\Unit\Customers\Customer\Application\ResetCustomer;

use App\Context\Customers\Customer\Application\ResetCustomer\ResetCustomerUseCase;
use App\Context\Customers\Customer\Domain\Event\CustomerWasReset;
use App\Context\Customers\Customer\Domain\Repository\CustomerRepository;
use App\Context\Customers\Customer\Domain\Customer;
use App\Context\Customers\Customer\Domain\ValueObject\CustomerId;
use App\Context\Customers\Customer\Domain\ValueObject\CustomerInsertedMoney;
use App\Context\Customers\Customer\Domain\ValueObject\CustomerStatus;
use App\Context\Items\Item\Domain\Item;
use App\Context\Items\Item\Domain\ValueObject\ItemId;
use App\SharedKernel\Domain\Bus\Event\EventBus;
use App\Tests\Unit\Coins\Coin\Domain\CoinMother;
use App\Tests\Unit\Customers\Customer\Domain\CustomerMother;
use App\Tests\Unit\Items\Item\Domain\ItemMother;
use App\Tests\Unit\SharedKernel\Domain\Mothers\IntMother;
use App\Tests\Unit\SharedKernel\Domain\Mothers\UuidMother;
use PHPUnit\Framework\TestCase;

class ResetCustomerUseCaseTest extends TestCase
{
    public function test_it_resets_customer_and_publishes_event(): void
    {
        $repository = $this->createMock(CustomerRepository::class);
        $eventBus = $this->createMock(EventBus::class);

        $customerId = CustomerId::random();
        $idProduct = ItemId::random();
        $insertedMoney = CustomerInsertedMoney::randomCoins(3);
        $status =  new CustomerStatus('IN_PROCESS');
        $remainingMachineCoins = CoinMother::createArray(3);

        $resetCustomer = Customer::reset($customerId, $idProduct, $insertedMoney, $status, $remainingMachineCoins);

        $repository
            ->expects(self::once())
            ->method('save')
            ->with($this->callback(function (Customer $savedCustomer) use ($resetCustomer) {
                return $savedCustomer->customer_id()->value() === $resetCustomer->customer_id()->value() &&
                    $savedCustomer->status()->value() === $resetCustomer->status()->value() &&
                    $savedCustomer->inserted_money() === $resetCustomer->inserted_money();
            }));

        $eventBus
            ->expects(self::once())
            ->method('publish')
            ->with($this->callback(function ($event) use ($resetCustomer) {
                return $event instanceof CustomerWasReset &&
                    $event->aggregateId() === $resetCustomer->customer_id()->value() &&
                    $event->customer_id()->value() === $resetCustomer->customer_id()->value() &&
                    $event->id_product()->value() === $resetCustomer->id_product()->value() &&
                    $event->inserted_money()->value() === $resetCustomer->inserted_money()->value() &&
                    $event->status()->value() === $resetCustomer->status()->value() &&
                    $event->remaining_machine_coins() === $resetCustomer->remaining_machine_coins();
            }));

        $useCase = new ResetCustomerUseCase($repository, $eventBus);
        $useCase->__invoke($customerId, $idProduct, $insertedMoney, $status, $remainingMachineCoins);
    }

}
