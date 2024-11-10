<?php

namespace App\Context\Items\Item\Application\GetItem;

use App\Context\Items\Event\ItemsQuery;

class GetItemQuery extends ItemsQuery
{

    public function __construct(private readonly string $item_id) {}


    public function item_id(): string
    {
        return $this->item_id;
    }

    protected function messageName(): string
    {
        return 'items.get';
    }
}
