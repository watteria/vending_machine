<?php

namespace App\Context\Customers\Customer\Domain\Event;

use App\Context\Customers\Event\CustomersDomainEvent;
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
        return new self($aggregateId, $body['customer_id'], $body['id_product'],$body['inserted_money'], $body['status'], $body['remaining_machine_coins'], $eventId, $occurredOn);
    }

    public static function eventName(): string
    {
        return 'customer.checkout';
    }
}
