<?php

namespace App\Context\Customers\Customer\UI\Controller;

use App\Context\Coins\Coin\Application\AllCoins\AllCoinsQuery;
use App\Context\Coins\Coin\Domain\Tools\MoneyChangeOnLimitedCoins;
use App\Context\Coins\Coin\Domain\Tools\MoneyCounterFromJson;
use App\Context\Customers\Customer\Application\CreateCustomer\CreateCustomerCommand;
use App\Context\Customers\Customer\Domain\ValueObject\CustomerId;
use App\Context\Customers\Customer\Domain\ValueObject\CustomerInsertedMoney;
use App\Context\Customers\Customer\Domain\ValueObject\CustomerStatus;
use App\Context\Items\Item\Domain\ValueObject\ItemId;
use App\SharedKernel\Domain\Bus\Command\CommandBus;
use App\SharedKernel\Domain\Bus\Query\QueryBus;
use Ramsey\Uuid\Uuid;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;


/***
 * Create a customer id and register in customers table
 * @param Request $request
 * @return Response
 */
class CreateCustomerController extends AbstractController
{
    public function __construct(private readonly CommandBus $commandBus,private readonly QueryBus $queryBus)
    {
    }

    public function __invoke(Request $request): Response
    {
        $customer_id=CustomerId::random();

        $jsonData = json_decode($request->getContent(), true);
        if($jsonData === null) {
            $jsonData= $request->request->all();
        }

        if(isset($jsonData['id_product']) && $jsonData['id_product']!=="" ) {
            $json_id_product = $jsonData['id_product'];
        }else{
            $json_id_product="";
        }

        // Get Machine current money
        $machineCoins=$this->queryBus->ask(AllCoinsQuery::create())->result();
        $change=MoneyChangeOnLimitedCoins::calculateChange($machineCoins,0,0);

        $this->commandBus->dispatch(new CreateCustomerCommand($customer_id, new ItemId($json_id_product),
            new CustomerInsertedMoney($jsonData['inserted_money']), new CustomerStatus('IN_PROCESS'),$change['coins_on_machine']));
        $total="";
        if(!empty($jsonData['inserted_money'])){
            $total=MoneyCounterFromJson::calculateTotal($jsonData['inserted_money']);
        }
        return new JsonResponse(['message' => "Customer created",'customer_id' => $customer_id->value(),'totalMonedas'=>$total], Response::HTTP_CREATED);
    }


}
