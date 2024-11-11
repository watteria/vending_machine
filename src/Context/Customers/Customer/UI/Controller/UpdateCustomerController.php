<?php

namespace App\Context\Customers\Customer\UI\Controller;

use App\Context\Coins\Coin\Application\AllCoins\AllCoinsQuery;
use App\Context\Coins\Coin\Domain\Tools\MoneyChangeOnLimitedCoins;
use App\Context\Coins\Coin\Domain\Tools\MoneyCounterFromJson;
use App\Context\Customers\Customer\Application\UpdateCustomer\UpdateCustomerCommand;
use App\SharedKernel\Domain\Bus\Command\CommandBus;
use App\SharedKernel\Domain\Bus\Query\QueryBus;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class UpdateCustomerController extends AbstractController
{
    public function __construct(private readonly CommandBus $commandBus,private readonly QueryBus $queryBus)
    {
    }

    public function __invoke(Request $request): Response
    {

        $jsonData = json_decode($request->getContent(), true);
        if($jsonData === null) {
            $jsonData= $request->request->all();
        }

        $json_inserted_money=json_encode($jsonData['inserted_money']);
        $json_id_product=$jsonData['id_product'];

        $total=MoneyCounterFromJson::calculateTotal($jsonData['inserted_money']);

        $machineCoins=$this->queryBus->ask(AllCoinsQuery::create())->result();
        $change=MoneyChangeOnLimitedCoins::calculateChange($machineCoins,0,0);

        $this->commandBus->dispatch(new UpdateCustomerCommand($request->get('customer_id'), $json_id_product ,
            $json_inserted_money, 'IN_PROCESS',json_encode($change['coins_on_machine'])));

        return new JsonResponse(['message' => "Customer updated",'totalMonedas'=>$total], Response::HTTP_CREATED);

    }

}
