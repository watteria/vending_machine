<?php

namespace App\Context\Customers\Customer\UI\Controller;

use App\Context\Coins\Coin\Application\AllCoins\AllCoinsQuery;
use App\Context\Coins\Coin\Domain\Tools\MoneyChangeOnLimitedCoins;
use App\Context\Coins\Coin\Domain\Tools\MoneyCounterFromJson;
use App\Context\Customers\Customer\Application\CheckoutCustomer\CheckoutCustomerCommand;
use App\Context\Customers\Customer\Application\CreateCustomer\CreateCustomerCommand;
use App\Context\Customers\Customer\Application\ResetCustomer\ResetCustomerCommand;
use App\Context\Customers\Customer\Application\UpdateCustomer\UpdateCustomerCommand;
use App\Context\Items\Item\Application\GetItem\GetItemQuery;
use App\SharedKernel\Domain\Bus\Command\CommandBus;
use App\SharedKernel\Domain\Bus\Query\QueryBus;
use Ramsey\Uuid\Uuid;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class ResetCustomerController extends AbstractController
{
    public function __construct(private readonly CommandBus $commandBus,private readonly QueryBus $queryBus)
    {
    }

    public function __invoke(Request $request): Response
    {

        if(!$request->get('customer_id')){
            return new JsonResponse(['message' => "Customer reset. Nothing to return."], Response::HTTP_CREATED);
        }else{
            $customer_id=$request->get('customer_id');
        }

        $jsonData = json_decode($request->getContent(), true);
        if($jsonData === null) {
            $jsonData= $request->request->all();
        }

        $json_inserted_money=json_encode($jsonData['inserted_money']);
        $json_id_product=$jsonData['id_product'];

        $machineCoins=$this->queryBus->ask(AllCoinsQuery::create())->result();
        $change=MoneyChangeOnLimitedCoins::calculateChange($machineCoins,0,0);

        $this->commandBus->dispatch(new ResetCustomerCommand($customer_id,$json_id_product , $json_inserted_money, 'CANCELLED',json_encode($change['coins_on_machine'])));
        return new JsonResponse(['message' => $jsonData['inserted_money'],'action'=>'return'],Response::HTTP_OK);

    }

}
