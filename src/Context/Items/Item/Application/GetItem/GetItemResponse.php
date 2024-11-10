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
            'item_id' => $this->item->item_id(),
            'product_name' => $this->item->product_name(),
            'quantity' => $this->item->quantity(),
            'price' => $this->item->price()
        ];
        return $result_final;
    }

}
