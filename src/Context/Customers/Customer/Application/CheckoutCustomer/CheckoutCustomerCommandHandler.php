<?php

namespace App\Context\Customers\Customer\Application\CheckoutCustomer;

use App\SharedKernel\Domain\Bus\Command\CommandHandler;

class CheckoutCustomerCommandHandler implements CommandHandler
{
    public function __construct(private readonly CheckoutCustomerUseCase $useCase)
    {
    }

    public function __invoke(CheckoutCustomerCommand $command): void
    {
        $this->useCase->__invoke(
            $command->customer_id(),
            $command->id_product(),
            $command->inserted_money(),
            $command->status(),
            $command->remaining_machine_coins()
        );
    }
}
