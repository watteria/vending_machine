<?php

namespace App\Context\Coins\Coin\Application\UpdateCoin;

use App\SharedKernel\Domain\Bus\Command\CommandHandler;

class UpdateCoinCommandHandler implements CommandHandler
{
    public function __construct(private readonly UpdateCoinUseCase $useCase)
    {
    }

    public function __invoke(UpdateCoinCommand $command): void
    {
        $this->useCase->__invoke(
            $command->coin_id(),
            $command->quantity(),
            $command->coin_value(),
            $command->valid_for_change()
        );
    }
}
