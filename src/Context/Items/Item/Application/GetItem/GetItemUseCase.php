<?php

namespace App\Context\Items\Item\Application\GetItem;

use App\Context\Items\Item\Domain\Item;
use App\Context\Items\Item\Domain\Repository\ItemRepository;

class GetItemUseCase
{
    public function __construct(private readonly ItemRepository $repository)
    {
    }

    /***
     * Get Item from item_id
     *
     * @param string $item_id
     * @return Item|null
     */
    public function __invoke(string $item_id): ? Item
    {
        $item = $this->repository->search($item_id);

        if (empty($item)) {
            return null;
        }
        return $item;
    }
}
