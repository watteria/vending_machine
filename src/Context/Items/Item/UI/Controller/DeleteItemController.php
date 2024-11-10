<?php

namespace App\Context\Items\Item\UI\Controller;

use App\Context\Items\Item\Application\DeleteItem\DeleteItemCommand;
use App\Context\Items\Item\Application\GetItem\GetItemQuery;
use App\SharedKernel\Domain\Bus\Command\CommandBus;
use App\SharedKernel\Domain\Bus\Query\QueryBus;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\ConstraintViolationListInterface;
use Symfony\Component\Validator\Validation;

class DeleteItemController extends AbstractController
{
    public function __construct(private readonly CommandBus $commandBus,private readonly QueryBus $queryBus)
    {
    }

    public function __invoke(Request $request): Response
    {

        $item_id=$request->get('item_id');

        $Item=$this->queryBus->ask(new GetItemQuery($item_id));

        $this->commandBus->dispatch(new DeleteItemCommand($Item->result()['item_id'], $Item->result()['product_name'],
            $Item->result()['quantity'], $Item->result()['price']));
        return new JsonResponse(['message' => "Item deleted"], Response::HTTP_OK);

    }

}
