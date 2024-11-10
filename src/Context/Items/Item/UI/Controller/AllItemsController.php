<?php

namespace App\Context\Items\Item\UI\Controller;

use App\Context\Items\Item\Application\AllItems\AllItemsQuery;
use App\SharedKernel\Domain\Bus\Query\QueryBus;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class AllItemsController extends AbstractController
{
    public function __construct(private readonly QueryBus $queryBus){}

    public function index(Request $request): Response
    {
        $response = $this->queryBus->ask(AllItemsQuery::create());

        return new JsonResponse($response->result());
    }


    protected function exceptions(): array
    {
        return [];
    }

}
