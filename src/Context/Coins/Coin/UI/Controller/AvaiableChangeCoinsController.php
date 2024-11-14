<?php

namespace App\Context\Coins\Coin\UI\Controller;

use App\Context\Coins\Coin\Application\AllCoins\AllCoinsQuery;
use App\SharedKernel\Domain\Bus\Query\QueryBus;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class AvaiableChangeCoinsController extends AbstractController
{
    public function __construct(private readonly QueryBus $queryBus){}

    /***
     * Get all coins with valid_for_change=true
     * @param Request $request
     * @return Response
     */
    public function __invoke(Request $request): Response
    {
        $response = $this->queryBus->ask(AllCoinsQuery::create());

        $coins=$response->result();

        $coinsForChange = array_filter($coins, function($coin) {
            return $coin['valid_for_change'] === true;
        });

        $coinsForChange = array_values($coinsForChange);


        return new JsonResponse($coinsForChange);
    }


    protected function exceptions(): array
    {
        return [];
    }

}
