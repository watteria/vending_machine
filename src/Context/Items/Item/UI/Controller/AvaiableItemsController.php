<?php

namespace App\Context\Items\Item\UI\Controller;

use App\Context\Items\Item\Application\AllItems\AllItemsQuery;
use App\SharedKernel\Domain\Bus\Query\QueryBus;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class AvaiableItemsController extends AbstractController
{
    public function __construct(private readonly QueryBus $queryBus){}

    /***
     * Get all avaiable items ( Quantity >0)
     *
     * @param Request $request
     * @return Response
     */

    public function __invoke(Request $request): Response
    {
        $response = $this->queryBus->ask(AllItemsQuery::create());

        $items=$response->result();

        $itemsAvaiable = array_filter($items, function($item) {
            return $item['quantity'] > 0;
        });

        $itemsAvaiable = array_values($itemsAvaiable);

        return new JsonResponse($itemsAvaiable);
    }


    protected function exceptions(): array
    {
        return [];
    }

}
