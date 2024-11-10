<?php

namespace App\Context\Items\Item\Application\DeleteItem;

use App\SharedKernel\Domain\Bus\Command\CommandHandler;

class DeleteItemCommandHandler implements CommandHandler
{
    public function __construct(private readonly DeleteItemUseCase $useCase)
    {
    }

    public function __invoke(DeleteItemCommand $command): void
    {
        $this->useCase->__invoke(
            $command->item_id(),
            $command->product_name(),
            $command->quantity(),
            $command->price()
        );
    }
}
