<?php

namespace App\Context\Items\Item\Application\OnCustomerCheckout;

use App\Context\Customers\Customer\Domain\Event\CustomerWasCheckout;
use App\Context\Items\Item\Application\GetItem\GetItemQuery;
use App\Context\Items\Item\Application\UpdateItem\UpdateItemCommand;
use App\Context\Items\Item\Domain\ValueObject\ItemId;
use App\Context\Items\Item\Domain\ValueObject\ItemPrice;
use App\Context\Items\Item\Domain\ValueObject\ItemProductName;
use App\Context\Items\Item\Domain\ValueObject\ItemQuantity;
use App\SharedKernel\Domain\Bus\Command\CommandBus;
use App\SharedKernel\Domain\Bus\Event\DomainEventSubscriber;
use App\SharedKernel\Domain\Bus\Query\QueryBus;

class OnCustomerCheckout implements DomainEventSubscriber
{

    public function __construct(private readonly CommandBus $commandBus,private readonly QueryBus $queryBus)
    {
    }

    public function __invoke(CustomerWasCheckout $event): void
    {

        $product=$this->queryBus->ask(new GetItemQuery($event->id_product()));
        $this->commandBus->dispatch(new UpdateItemCommand(new ItemId($product['item_id']), new ItemProductName($product['product_name']),
            new ItemQuantity($product['quantity']-1), new ItemPrice($product['price'])));
    }

    public static function subscribedTo(): array
    {
        return [CustomerWasCheckout::class];
    }

}
