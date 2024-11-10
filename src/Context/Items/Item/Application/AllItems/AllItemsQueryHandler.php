<?php

namespace App\Context\Items\Item\Application\AllItems;

use App\Context\Items\Item\Domain\Item;
use App\Context\Items\Item\UI\Controller\AllItemsController;
use App\SharedKernel\Domain\Aggregate\Exception\AggregateNotFoundException;
use App\SharedKernel\Domain\Bus\Query\QueryHandler;
use App\SharedKernel\Domain\Bus\Query\Response;

class AllItemsQueryHandler implements QueryHandler
{
    public function __construct(private readonly AllItemsUseCase $useCase)
    {
    }

    public function __invoke(AllItemsQuery $query): Response
    {
        $items = $this->useCase->__invoke();
        if (null === $items) {
            throw AggregateNotFoundException::fromAggregate(Item::class);
        }

        return new AllItemsResponse($items);
    }
}
