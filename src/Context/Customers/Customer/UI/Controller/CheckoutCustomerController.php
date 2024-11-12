<?php

namespace App\Context\Customers\Customer\UI\Controller;

use App\Context\Coins\Coin\Application\AllCoins\AllCoinsQuery;
use App\Context\Coins\Coin\Domain\Tools\MoneyChangeOnLimitedCoins;
use App\Context\Coins\Coin\Domain\Tools\MoneyCounterFromJson;
use App\Context\Customers\Customer\Application\CheckoutCustomer\CheckoutCustomerCommand;
use App\Context\Customers\Customer\Domain\ValueObject\CustomerId;
use App\Context\Customers\Customer\Domain\ValueObject\CustomerInsertedMoney;
use App\Context\Customers\Customer\Domain\ValueObject\CustomerStatus;
use App\Context\Items\Item\Application\GetItem\GetItemQuery;
use App\Context\Items\Item\Domain\ValueObject\ItemId;
use App\SharedKernel\Domain\Bus\Command\CommandBus;
use App\SharedKernel\Domain\Bus\Query\QueryBus;
use Ramsey\Uuid\Uuid;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckoutCustomerController extends AbstractController
{
    public function __construct(private readonly CommandBus $commandBus,private readonly QueryBus $queryBus)
    {
    }

    public function __invoke(Request $request): Response
    {

        if(!$request->get('customer_id')){
            $customer_id=Uuid::uuid4()->toString();
        }else{
            $customer_id=$request->get('customer_id');
        }

        $jsonData = json_decode($request->getContent(), true);
        if($jsonData === null) {
            $jsonData= $request->request->all();
        }




        if(isset($jsonData['id_product']) && $jsonData['id_product']!=="" ){
            $Item=$this->queryBus->ask(new GetItemQuery($jsonData['id_product']));

            if(!isset($Item->result()['quantity'])){
                return new JsonResponse(['message' => "ERROR: This Product is not found"],Response::HTTP_OK);
            }else{

                if($jsonData['inserted_money']!="" && $jsonData['inserted_money']){
                    $total=MoneyCounterFromJson::calculateTotal($jsonData['inserted_money']);

                    if($total==0){
                        return new JsonResponse(['message' => "ERROR: No coin Inserted"],Response::HTTP_OK);
                    }else{
                        if($Item->result()['quantity']==0){
                            return new JsonResponse(['message' => "ERROR: This Product is out of stock"],Response::HTTP_OK);
                        }else{

                            if($Item->result()['price']>$total){
                                return new JsonResponse(['message' => "ERROR: You haven't inserted enought money"],Response::HTTP_OK);
                            }else{

                                $machineCoins=$this->queryBus->ask(AllCoinsQuery::create())->result();

                                $change=MoneyChangeOnLimitedCoins::calculateChange($machineCoins,$jsonData['inserted_money'],$Item->result()['price']);

                                if($change['status']=="insufficient_machine_change"){
                                    return new JsonResponse(['message' => "ERROR: The machine not have enought money for the change"],Response::HTTP_OK);
                                }

                                $this->commandBus->dispatch(new CheckoutCustomerCommand(new CustomerId($customer_id),new ItemId($jsonData['id_product']) , new CustomerInsertedMoney($jsonData['inserted_money']), new CustomerStatus('COMPLETED'),$change['coins_on_machine']));


                                return new JsonResponse( $change,Response::HTTP_OK);

                            }
                        }

                    }
                }else{
                    return new JsonResponse(['message' => "ERROR: No coin Inserted"],Response::HTTP_OK);
                }

            }
        }else{
            $error="ERROR: No Product Selected";
            return new JsonResponse(['message' =>$error],Response::HTTP_OK);
        }

    }

}
