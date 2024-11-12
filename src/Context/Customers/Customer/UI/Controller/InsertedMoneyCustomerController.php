<?php

namespace App\Context\Customers\Customer\UI\Controller;

use App\Context\Coins\Coin\Application\AllCoins\AllCoinsQuery;
use App\Context\Coins\Coin\Domain\Tools\MoneyChangeOnLimitedCoins;
use App\Context\Customers\Customer\Application\GetCustomer\GetCustomerQuery;
use App\SharedKernel\Domain\Bus\Query\QueryBus;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class InsertedMoneyCustomerController extends AbstractController
{
    public function __construct(private readonly QueryBus $queryBus){}

    public function __invoke(Request $request): Response
    {

        $customer_id=$request->get('customer_id');

        $response = $this->queryBus->ask(new GetCustomerQuery($customer_id));

        $customer=$response->result();

        return new JsonResponse($customer['inserted_money']);
    }


    protected function exceptions(): array
    {
        return [];
    }

}
