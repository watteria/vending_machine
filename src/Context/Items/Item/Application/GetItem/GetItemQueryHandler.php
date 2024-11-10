<?php

namespace App\Context\Items\Item\Application\GetItem;

use App\Context\Items\Item\Domain\Item;
use App\SharedKernel\Domain\Aggregate\Exception\AggregateNotFoundException;
use App\SharedKernel\Domain\Bus\Query\QueryHandler;
use App\SharedKernel\Domain\Bus\Query\Response;

class GetItemQueryHandler implements QueryHandler
{
    public function __construct(private readonly GetItemUseCase $useCase)
    {
    }

    public function __invoke(GetItemQuery $query): Response
    {


         $items = $this->useCase->__invoke( $query->item_id());

        if (null === $items) {
            throw AggregateNotFoundException::fromAggregate(Item::class);
        }

        return new GetItemResponse($items);
    }
}
