<?php

namespace App\Context\Items\Item\Domain\Event;

use App\Context\Items\Event\ItemsDomainEvent;
use App\SharedKernel\Domain\Bus\Event\DomainEvent;

class ItemWasCreated extends ItemsDomainEvent
{

    public static function fromPrimitives(
        string $aggregateId,
        array $body,
        string $eventId,
        string $occurredOn
    ): DomainEvent
    {
        return new self($aggregateId, $body['item_id'], $body['product_name'],$body['quantity'], $body['price'], $eventId, $occurredOn);
    }

    public static function eventName(): string
    {
        return 'item.item_created';
    }
}
