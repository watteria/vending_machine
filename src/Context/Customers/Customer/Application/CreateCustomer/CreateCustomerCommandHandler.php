<?php

namespace App\Context\Customers\Customer\Application\CreateCustomer;

use App\SharedKernel\Domain\Bus\Command\CommandHandler;

class CreateCustomerCommandHandler implements CommandHandler
{
    public function __construct(private readonly CreateCustomerUseCase $useCase)
    {
    }

    public function __invoke(CreateCustomerCommand $command): void
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
