<?php

namespace App\Context\Customers\Customer\Application\UpdateCustomer;

use App\SharedKernel\Domain\Bus\Command\CommandHandler;

class UpdateCustomerCommandHandler implements CommandHandler
{
    public function __construct(private readonly UpdateCustomerUseCase $useCase)
    {
    }

    public function __invoke(UpdateCustomerCommand $command): void
    {
        $this->useCase->__invoke(
            $command->customer_id(),
            $command->id_product(),
            $command->inserted_money(),
            $command->status(),
            $command->remaining_machine_coins(),
        );
    }
}
