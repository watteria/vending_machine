<?php

namespace App\Context\Items\Item\Application\UpdateItem;

use App\Context\Items\Item\Domain\Repository\ItemRepository;
use App\Context\Items\Item\Domain\Item;
use App\SharedKernel\Domain\Bus\Event\EventBus;

class UpdateItemUseCase
{
    public function __construct(
        private readonly ItemRepository $repository,
        private readonly EventBus $eventBus
    ) {
    }

    public function __invoke(string $item_id, string $product_name,int $quantity,float $price): void
    {
        $item = Item::update($item_id, $product_name,$quantity,$price);

        $this->repository->save($item);

        $this->eventBus->publish(...$item->pullDomainEvents());
    }
}
