<?php

namespace App\Context\Coins\Coin\Application\OnCustomerCheckout;

use App\Context\Coins\Coin\Application\UpdateCoin\UpdateCoinCommand;
use App\Context\Coins\Coin\Domain\ValueObject\CoinId;
use App\Context\Coins\Coin\Domain\ValueObject\CoinQuantity;
use App\Context\Coins\Coin\Domain\ValueObject\CoinValidForChange;
use App\Context\Coins\Coin\Domain\ValueObject\CoinValue;
use App\Context\Customers\Customer\Domain\Event\CustomerWasCheckout;
use App\SharedKernel\Domain\Bus\Command\CommandBus;
use App\SharedKernel\Domain\Bus\Event\DomainEventSubscriber;

class OnCustomerCheckout implements DomainEventSubscriber
{

    public function __construct(private readonly CommandBus $commandBus)
    {
    }

    public function __invoke(CustomerWasCheckout $event): void
    {
            $coins=$event->remaining_machine_coins() ;

            foreach ($coins as $coin) {
                $this->commandBus->dispatch(new UpdateCoinCommand(new CoinId($coin['coin_id']),
                    new CoinQuantity($coin['quantity']),new CoinValue($coin['coin_value']), new CoinValidForChange($coin['valid_for_change'])));
            }

    }

    public static function subscribedTo(): array
    {
        return [CustomerWasCheckout::class];
    }

}
