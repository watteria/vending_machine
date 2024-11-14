<?php

namespace App\Context\Items\Item\Application\AllItems;

use App\Context\Items\Item\Domain\Repository\ItemRepository;

class AllItemsUseCase
{
    public function __construct(private readonly ItemRepository $repository)
    {
    }

    /***
     * Get all items in the repository
     * @return array|null
     */
    public function __invoke(): ? array
    {
        $items = $this->repository->searchAll();

        if (empty($items)) {
            return null;
        }

        return $items;
    }
}
