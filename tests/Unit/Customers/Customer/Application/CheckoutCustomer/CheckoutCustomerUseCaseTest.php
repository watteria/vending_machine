<?php

namespace App\Tests\Unit\Customers\Customer\Application\CheckoutCustomer;

use App\Context\Customers\Customer\Application\CheckoutCustomer\CheckoutCustomerUseCase;
use App\Context\Customers\Customer\Application\CreateCustomer\CreateCustomerUseCase;
use App\Context\Customers\Customer\Domain\Event\CustomerWasCheckout;
use App\Context\Customers\Customer\Domain\Repository\CustomerRepository;
use App\Context\Customers\Customer\Domain\Customer;
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

        $customerId = UuidMother::create();
        $idProduct = json_encode(ItemMother::create());
        $insertedMoney = json_encode(CoinMother::createArray(3));
        $status = 'PROCESSING';
        $remainingMachineCoins = IntMother::create(2);

        $checkoutCustomer = Customer::checkout($customerId, $idProduct, $insertedMoney, $status, $remainingMachineCoins);

        $repository
            ->expects(self::once())
            ->method('save')
            ->with($this->callback(function (Customer $savedCustomer) use ($checkoutCustomer) {
                return $checkoutCustomer->id() === $checkoutCustomer->id() &&
                    $checkoutCustomer->status() === $checkoutCustomer->status() &&
                    $checkoutCustomer->inserted_money() === $checkoutCustomer->inserted_money();
            }));

        $eventBus
            ->expects(self::once())
            ->method('publish')
            ->with($this->callback(function ($event) use ($checkoutCustomer) {
                return $event instanceof CustomerWasCheckout &&
                    $event->aggregateId() === $checkoutCustomer->id() &&
                    $event->customer_id() === $checkoutCustomer->id() &&
                    $event->id_product() === $checkoutCustomer->id_product() &&
                    $event->inserted_money() === $checkoutCustomer->inserted_money() &&
                    $event->status()=== $checkoutCustomer->status() &&
                    $event->remaining_machine_coins() === $checkoutCustomer->remaining_machine_coins();
            }));

        $useCase = new CheckoutCustomerUseCase($repository, $eventBus);
        $useCase->__invoke($customerId, $idProduct, $insertedMoney, $status, $remainingMachineCoins);
    }
}
