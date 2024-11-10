<?php

namespace App\Context\Items\Item\Application\UpdateItem;

use App\SharedKernel\Domain\Bus\Command\CommandHandler;

class UpdateItemCommandHandler implements CommandHandler
{
    public function __construct(private readonly UpdateItemUseCase $useCase)
    {
    }

    public function __invoke(UpdateItemCommand $command): void
    {
        $this->useCase->__invoke(
            $command->item_id(),
            $command->product_name(),
            $command->quantity(),
            $command->price()
        );
    }
}
