<?php

namespace App\Context\Items\Item\Application\GetItem;

use App\Context\Items\Item\Domain\Item;
use App\Context\Items\Item\Domain\Repository\ItemRepository;

class GetItemUseCase
{
    public function __construct(private readonly ItemRepository $repository)
    {
    }

    public function __invoke(string $item_id): ? Item
    {
        $item = $this->repository->search($item_id);

        if (empty($item)) {
            return null;
        }
        return $item;
    }
}
