<?php

namespace App\Context\Items\Item\Domain\Repository;

use App\Context\Items\Item\Domain\Item;

/***
 * Item Repository interface
 */
interface ItemRepository
{

    public function searchAll(): array;
    public function search($item_id): ?Item;

    public function save(Item $item): void;
    public function delete(Item $item): void;
}
