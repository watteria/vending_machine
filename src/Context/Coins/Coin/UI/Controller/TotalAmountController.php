<?php

namespace App\Context\Coins\Coin\UI\Controller;

use App\Context\Coins\Coin\Application\TotalAmount\TotalAmountQuery;
use App\Context\Coins\Coin\Domain\Tools\MoneyCounterFromJson;
use App\SharedKernel\Domain\Bus\Query\QueryBus;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class TotalAmountController extends AbstractController
{
    public function __construct(private readonly QueryBus $queryBus){}

    /***
     * Get total euro amount in vending machine
     *
     * @param Request $request
     * @return Response
     */
    public function __invoke(Request $request): Response
    {
        $response = $this->queryBus->ask(TotalAmountQuery::create());

        $total=MoneyCounterFromJson::calculateTotal($response->result());
        return new JsonResponse(['total'=>$total]);
    }


    protected function exceptions(): array
    {
        return [];
    }

}
