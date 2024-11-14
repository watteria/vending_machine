<?php

namespace App\Context\Customers\Customer\Domain\Event;

use App\Context\Customers\Event\CustomersDomainEvent;
use App\SharedKernel\Domain\Bus\Event\DomainEvent;

class CustomerWasCreated extends CustomersDomainEvent
{
    /***
     * Read the data in the message in order to recreate the instance
     *
     * @param string $aggregateId
     * @param array $body
     * @param string $eventId
     * @param string $occurredOn
     * @return DomainEvent
     */
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
        return 'customer.customer_created';
    }
}
