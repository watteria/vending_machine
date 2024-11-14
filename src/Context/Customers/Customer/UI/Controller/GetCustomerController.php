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

class GetCustomerController extends AbstractController
{
    public function __construct(private readonly QueryBus $queryBus){}

    /***
     * Get Customer info and current machine money
     * @param Request $request
     * @return Response
     */
    public function __invoke(Request $request): Response
    {

        // Get user current info
        $response = $this->queryBus->ask(new GetCustomerQuery($request->get('customer_id')));
        $customer=$response->result();

        // Get machine money
        $machineCoins=$this->queryBus->ask(AllCoinsQuery::create())->result();
        $change=MoneyChangeOnLimitedCoins::calculateChange($machineCoins,0,0);

        $customer['remaining_machine_coins']=$change['coins_on_machine'];

        return new JsonResponse($customer);
    }


    protected function exceptions(): array
    {
        return [];
    }

}
