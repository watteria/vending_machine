<?php

namespace App\Tests\Unit\Customers\Customer\Application\ResetCustomer;

use App\Context\Customers\Customer\Application\ResetCustomer\ResetCustomerUseCase;
use App\Context\Customers\Customer\Domain\Event\CustomerWasReset;
use App\Context\Customers\Customer\Domain\Repository\CustomerRepository;
use App\Context\Customers\Customer\Domain\Customer;
use App\Context\Items\Item\Domain\Item;
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

        $customerId = UuidMother::create();
        $idProduct = json_encode(ItemMother::create());
        $insertedMoney = json_encode(CoinMother::createArray(3));
        $status = 'PROCESSING';
        $remainingMachineCoins = IntMother::create(2);

        $resetCustomer = Customer::reset($customerId, $idProduct, $insertedMoney, $status, $remainingMachineCoins);

        $repository
            ->expects(self::once())
            ->method('save')
            ->with($this->callback(function (Customer $savedCustomer) use ($resetCustomer) {
                return $savedCustomer->customer_id() === $resetCustomer->customer_id() &&
                    $savedCustomer->status() === $resetCustomer->status() &&
                    $savedCustomer->inserted_money() === $resetCustomer->inserted_money();
            }));

        $eventBus
            ->expects(self::once())
            ->method('publish')
            ->with($this->callback(function ($event) use ($resetCustomer) {
                return $event instanceof CustomerWasReset &&
                    $event->aggregateId() === $resetCustomer->customer_id() &&
                    $event->customer_id() === $resetCustomer->customer_id() &&
                    $event->id_product() === $resetCustomer->id_product() &&
                    $event->inserted_money() === $resetCustomer->inserted_money() &&
                    $event->status() === $resetCustomer->status() &&
                    $event->remaining_machine_coins() === $resetCustomer->remaining_machine_coins();
            }));

        $useCase = new ResetCustomerUseCase($repository, $eventBus);
        $useCase->__invoke($customerId, $idProduct, $insertedMoney, $status, $remainingMachineCoins);
    }

}
