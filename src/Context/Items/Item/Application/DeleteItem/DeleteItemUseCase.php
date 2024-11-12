<?php

namespace App\Context\Items\Item\Application\DeleteItem;

use App\Context\Items\Item\Domain\Repository\ItemRepository;
use App\Context\Items\Item\Domain\Item;
use App\Context\Items\Item\Domain\ValueObject\ItemId;
use App\Context\Items\Item\Domain\ValueObject\ItemPrice;
use App\Context\Items\Item\Domain\ValueObject\ItemProductName;
use App\Context\Items\Item\Domain\ValueObject\ItemQuantity;
use App\SharedKernel\Domain\Bus\Event\EventBus;

class DeleteItemUseCase
{
    public function __construct(
        private readonly ItemRepository $repository,
        private readonly EventBus $eventBus
    ) {
    }

    public function __invoke(ItemId $item_id, ItemProductName $product_name,ItemQuantity $quantity,ItemPrice $price): void
    {
        $item = Item::delete($item_id, $product_name,$quantity,$price);

        $this->repository->delete($item);

        $this->eventBus->publish(...$item->pullDomainEvents());
    }
}
