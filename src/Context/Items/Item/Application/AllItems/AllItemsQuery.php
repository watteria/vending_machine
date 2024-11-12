<?php

namespace App\Context\Items\Item\Application\AllItems;

use App\Context\Items\Event\ItemsQuery;

class AllItemsQuery extends ItemsQuery
{

    public static function create(): self
    {

        return new self();
    }


}
