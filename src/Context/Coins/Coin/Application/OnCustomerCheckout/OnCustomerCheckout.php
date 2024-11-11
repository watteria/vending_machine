<?php

namespace App\Context\Coins\Coin\Application\OnCustomerCheckout;

use App\Context\Coins\Coin\Application\UpdateCoin\UpdateCoinCommand;
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
            $coins=json_decode($event->remaining_machine_coins() );

            foreach ($coins as $coin) {
                $this->commandBus->dispatch(new UpdateCoinCommand($coin->coin_id,
                    $coin->quantity, $coin->coin_value, $coin->valid_for_change));
            }

    }

    public static function subscribedTo(): array
    {
        return [CustomerWasCheckout::class];
    }

}
