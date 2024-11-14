<?php

namespace App\Context\Customers\Customer\UI\Controller;

use App\Context\Coins\Coin\Application\AllCoins\AllCoinsQuery;
use App\Context\Coins\Coin\Domain\Tools\MoneyChangeOnLimitedCoins;
use App\Context\Coins\Coin\Domain\Tools\MoneyCounterFromJson;
use App\Context\Customers\Customer\Application\CheckoutCustomer\CheckoutCustomerCommand;
use App\Context\Customers\Customer\Application\CreateCustomer\CreateCustomerCommand;
use App\Context\Customers\Customer\Application\ResetCustomer\ResetCustomerCommand;
use App\Context\Customers\Customer\Application\UpdateCustomer\UpdateCustomerCommand;
use App\Context\Customers\Customer\Domain\ValueObject\CustomerId;
use App\Context\Customers\Customer\Domain\ValueObject\CustomerInsertedMoney;
use App\Context\Customers\Customer\Domain\ValueObject\CustomerStatus;
use App\Context\Items\Item\Application\GetItem\GetItemQuery;
use App\SharedKernel\Domain\Bus\Command\CommandBus;
use App\SharedKernel\Domain\Bus\Query\QueryBus;
use App\Context\Items\Item\Domain\ValueObject\ItemId;
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


    /***
     * Return inserted money to customer and change customer state to cancelled in db
     *
     * @param Request $request
     * @return Response
     */
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



        $machineCoins=$this->queryBus->ask(AllCoinsQuery::create())->result();
        $change=MoneyChangeOnLimitedCoins::calculateChange($machineCoins,0,0);
        if($jsonData['id_product']===null){
            $jsonData['id_product']=ItemId::random();
        }
        $this->commandBus->dispatch(new ResetCustomerCommand(new CustomerId($customer_id),new ItemId($jsonData['id_product']) , new CustomerInsertedMoney($jsonData['inserted_money']),new CustomerStatus( 'CANCELLED'),$change['coins_on_machine']));
        return new JsonResponse(['message' => $jsonData['inserted_money'],'action'=>'return'],Response::HTTP_OK);

    }

}
