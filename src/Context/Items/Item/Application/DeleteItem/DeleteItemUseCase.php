<?php

namespace App\Context\Items\Item\Application\DeleteItem;

use App\Context\Items\Item\Domain\Repository\ItemRepository;
use App\Context\Items\Item\Domain\Item;
use App\SharedKernel\Domain\Bus\Event\EventBus;

class DeleteItemUseCase
{
    public function __construct(
        private readonly ItemRepository $repository,
        private readonly EventBus $eventBus
    ) {
    }

    public function __invoke(string $item_id, string $product_name,int $quantity,float $price): void
    {
        $item = Item::delete($item_id, $product_name,$quantity,$price);

        $this->repository->delete($item);

        $this->eventBus->publish(...$item->pullDomainEvents());
    }
}
