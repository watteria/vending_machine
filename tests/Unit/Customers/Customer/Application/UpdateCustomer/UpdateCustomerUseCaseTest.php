<?php

namespace App\Tests\Unit\Customers\Customer\Application\UpdateCustomer;

use App\Context\Customers\Customer\Application\UpdateCustomer\UpdateCustomerUseCase;
use App\Context\Customers\Customer\Domain\Event\CustomerWasUpdated;
use App\Context\Customers\Customer\Domain\Repository\CustomerRepository;
use App\Context\Customers\Customer\Domain\Customer;
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

        $customerId = UuidMother::create();
        $idProduct = json_encode(ItemMother::create());
        $insertedMoney = json_encode(CoinMother::createArray(3));
        $status = 'PROCESSING';
        $remainingMachineCoins = IntMother::create(2);

        $updatedCustomer = Customer::update($customerId, $idProduct, $insertedMoney, $status, $remainingMachineCoins);

        $repository
            ->expects(self::once())
            ->method('save')
            ->with($this->callback(function (Customer $savedCustomer) use ($updatedCustomer) {
                return $savedCustomer->id() === $updatedCustomer->id() &&
                    $savedCustomer->status() === $updatedCustomer->status() &&
                    $savedCustomer->inserted_money() === $updatedCustomer->inserted_money();
            }));

        $eventBus
            ->expects(self::once())
            ->method('publish')
            ->with($this->callback(function ($event) use ($updatedCustomer) {
                return $event instanceof CustomerWasUpdated &&
                    $event->aggregateId() === $updatedCustomer->id() &&
                    $event->customer_id() === $updatedCustomer->id() &&
                    $event->id_product() === $updatedCustomer->id_product() &&
                    $event->inserted_money() === $updatedCustomer->inserted_money() &&
                    $event->status()=== $updatedCustomer->status() &&
                    $event->remaining_machine_coins() === $updatedCustomer->remaining_machine_coins();
            }));

        $useCase = new UpdateCustomerUseCase($repository, $eventBus);
        $useCase->__invoke($customerId, $idProduct, $insertedMoney, $status, $remainingMachineCoins);
    }
}
