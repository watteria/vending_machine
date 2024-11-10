<?php

namespace App\Context\Items\Item\Application\CreateItem;

use App\SharedKernel\Domain\Bus\Command\CommandHandler;

class CreateItemCommandHandler implements CommandHandler
{
    public function __construct(private readonly CreateItemUseCase $useCase)
    {
    }

    public function __invoke(CreateItemCommand $command): void
    {
        $this->useCase->__invoke(
            $command->item_id(),
            $command->product_name(),
            $command->quantity(),
            $command->price()
        );
    }
}
