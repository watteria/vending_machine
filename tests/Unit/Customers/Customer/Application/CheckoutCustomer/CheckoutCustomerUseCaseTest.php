<?php

namespace App\Tests\Unit\Customers\Customer\Application\CheckoutCustomer;

use App\Context\Customers\Customer\Application\CheckoutCustomer\CheckoutCustomerUseCase;
use App\Context\Customers\Customer\Application\CreateCustomer\CreateCustomerUseCase;
use App\Context\Customers\Customer\Domain\Event\CustomerWasCheckout;
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

class CheckoutCustomerUseCaseTest extends TestCase
{
    public function test_it_checkout_customer_and_publishes_event(): void
    {
        $repository = $this->createMock(CustomerRepository::class);
        $eventBus = $this->createMock(EventBus::class);

        $customerId = CustomerId::random();
        $idProduct = ItemId::random();
        $insertedMoney = CustomerInsertedMoney::randomCoins(3);
        $status = new CustomerStatus('IN_PROCESS');
        $remainingMachineCoins = CoinMother::createArray(3);

        $checkoutCustomer = Customer::checkout($customerId, $idProduct, $insertedMoney, $status, $remainingMachineCoins);

        $repository
            ->expects(self::once())
            ->method('save')
            ->with($this->callback(function (Customer $savedCustomer) use ($checkoutCustomer) {
                return $checkoutCustomer->id()->value() === $checkoutCustomer->id()->value() &&
                    $checkoutCustomer->status()->value() === $checkoutCustomer->status()->value() &&
                    $checkoutCustomer->inserted_money() === $checkoutCustomer->inserted_money();
            }));

        $eventBus
            ->expects(self::once())
            ->method('publish')
            ->with($this->callback(function ($event) use ($checkoutCustomer) {
                return $event instanceof CustomerWasCheckout &&
                    $event->aggregateId() === $checkoutCustomer->id()->value() &&
                    $event->customer_id()->value() === $checkoutCustomer->id()->value() &&
                    $event->id_product()->value() === $checkoutCustomer->id_product()->value() &&
                    $event->inserted_money()->value() === $checkoutCustomer->inserted_money()->value() &&
                    $event->status()->value() === $checkoutCustomer->status()->value() &&
                    $event->remaining_machine_coins() === $checkoutCustomer->remaining_machine_coins();
            }));

        $useCase = new CheckoutCustomerUseCase($repository, $eventBus);
        $useCase->__invoke($customerId, $idProduct, $insertedMoney, $status, $remainingMachineCoins);
    }
}
