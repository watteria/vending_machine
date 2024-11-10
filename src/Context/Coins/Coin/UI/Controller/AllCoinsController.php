<?php

namespace App\Context\Coins\Coin\UI\Controller;

use App\Context\Coins\Coin\Application\AllCoins\AllCoinsQuery;
use App\SharedKernel\Domain\Bus\Query\QueryBus;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class AllCoinsController extends AbstractController
{
    public function __construct(private readonly QueryBus $queryBus){}

    public function index(Request $request): Response
    {
        $response = $this->queryBus->ask(AllCoinsQuery::create());

        return new JsonResponse($response->result());
    }


    protected function exceptions(): array
    {
        return [];
    }

}
