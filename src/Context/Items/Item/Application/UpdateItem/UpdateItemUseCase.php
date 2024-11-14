<?php

namespace App\Context\Items\Item\Application\UpdateItem;

use App\Context\Items\Item\Domain\Repository\ItemRepository;
use App\Context\Items\Item\Domain\Item;
use App\Context\Items\Item\Domain\ValueObject\ItemId;
use App\Context\Items\Item\Domain\ValueObject\ItemPrice;
use App\Context\Items\Item\Domain\ValueObject\ItemProductName;
use App\Context\Items\Item\Domain\ValueObject\ItemQuantity;
use App\SharedKernel\Domain\Bus\Event\EventBus;

class UpdateItemUseCase
{
    public function __construct(
        private readonly ItemRepository $repository,
        private readonly EventBus $eventBus
    ) {
    }

    /***
     * Update Item values
     *
     * @param ItemId $item_id
     * @param ItemProductName $product_name
     * @param ItemQuantity $quantity
     * @param ItemPrice $price
     * @return void
     */
    public function __invoke(ItemId $item_id, ItemProductName $product_name,ItemQuantity $quantity,ItemPrice $price): void
    {
        $item = Item::update($item_id, $product_name,$quantity,$price);

        $this->repository->save($item);

        $this->eventBus->publish(...$item->pullDomainEvents());
    }
}
