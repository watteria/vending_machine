<?php

namespace App\Context\Customers\Customer\Application\ResetCustomer;

use App\SharedKernel\Domain\Bus\Command\CommandHandler;

class ResetCustomerCommandHandler implements CommandHandler
{
    public function __construct(private readonly ResetCustomerUseCase $useCase)
    {
    }

    public function __invoke(ResetCustomerCommand $command): void
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
