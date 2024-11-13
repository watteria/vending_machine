<?php

namespace App\Context\Customers\Customer\Domain\Event;

use App\Context\Customers\Customer\Domain\ValueObject\CustomerId;
use App\Context\Customers\Customer\Domain\ValueObject\CustomerInsertedMoney;
use App\Context\Customers\Customer\Domain\ValueObject\CustomerStatus;
use App\Context\Customers\Event\CustomersDomainEvent;
use App\Context\Items\Item\Domain\ValueObject\ItemId;
use App\SharedKernel\Domain\Bus\Event\DomainEvent;

class CustomerWasCheckout extends CustomersDomainEvent
{

    public static function fromPrimitives(
        string $aggregateId,
        array $body,
        string $eventId,
        string $occurredOn
    ): DomainEvent
    {
        return new self(
            $aggregateId,
            new CustomerId($body['customer_id']) ,
            new ItemId($body['id_product']),
            new CustomerInsertedMoney($body['inserted_money']) ,
            new CustomerStatus($body['status']) ,
            $body['remaining_machine_coins'] ,
            $eventId,
            $occurredOn
        );
    }

    public static function eventName(): string
    {
        return 'customer.checkout';
    }
}
