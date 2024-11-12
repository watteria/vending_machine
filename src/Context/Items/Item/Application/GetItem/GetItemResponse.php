<?php

namespace App\Context\Items\Item\Application\GetItem;

use App\Context\Items\Item\Domain\Item;
use App\SharedKernel\Domain\Bus\Query\Response;

class GetItemResponse implements Response
{
    public function __construct(private readonly Item $item)
    {
    }

    public function result(): array
    {

        $result_final=[
            'item_id' => $this->item->item_id()->value(),
            'product_name' => $this->item->product_name()->value(),
            'quantity' => $this->item->quantity()->value(),
            'price' => $this->item->price()->value()
        ];
        return $result_final;
    }

}
