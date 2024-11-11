<?php

namespace App\Tests\Unit\Customers\Customer\Application\CreateCustomer;

use App\Context\Customers\Customer\Application\CreateCustomer\CreateCustomerUseCase;
use App\Context\Customers\Customer\Domain\Event\CustomerWasCreated;
use App\Context\Customers\Customer\Domain\Repository\CustomerRepository;
use App\Context\Customers\Customer\Domain\Customer;
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

        $customerId = UuidMother::create();
        $idProduct = json_encode(ItemMother::create());
        $insertedMoney = json_encode(CoinMother::createArray(3));
        $status = 'PROCESSING';
        $remainingMachineCoins = IntMother::create(2);

        $createdCustomer = Customer::create($customerId, $idProduct, $insertedMoney, $status, $remainingMachineCoins);

        $repository
            ->expects(self::once())
            ->method('save')
            ->with($this->callback(function (Customer $savedCustomer) use ($createdCustomer) {
                return $savedCustomer->id() === $createdCustomer->id() &&
                    $savedCustomer->status() === $createdCustomer->status() &&
                    $savedCustomer->inserted_money() === $createdCustomer->inserted_money();
            }));

        $eventBus
            ->expects(self::once())
            ->method('publish')
            ->with($this->callback(function ($event) use ($createdCustomer) {
                return $event instanceof CustomerWasCreated &&
                    $event->aggregateId() === $createdCustomer->id() &&
                    $event->customer_id() === $createdCustomer->id() &&
                    $event->id_product() === $createdCustomer->id_product() &&
                    $event->inserted_money() === $createdCustomer->inserted_money() &&
                    $event->status()=== $createdCustomer->status() &&
                    $event->remaining_machine_coins() === $createdCustomer->remaining_machine_coins();
            }));

        $useCase = new CreateCustomerUseCase($repository, $eventBus);
        $useCase->__invoke($customerId, $idProduct, $insertedMoney, $status, $remainingMachineCoins);
    }
}
