<?php

namespace App\Context\Items\Item\Application\AllItems;

use App\SharedKernel\Domain\Bus\Query\Response;

class AllItemsResponse implements Response
{
    public function __construct(private readonly array $items)
    {
    }

    public function result(): array
    {
        $result_final=array();
        foreach ($this->items as $item){
            $result_final[]=[
                'item_id' => $item->item_id()->value(),
                'product_name' => $item->product_name()->value(),
                'quantity' => $item->quantity()->value(),
                'price' => $item->price()->value()
            ];
        }
        return $result_final;
    }

}
