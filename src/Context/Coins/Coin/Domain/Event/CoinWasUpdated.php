<?php

namespace App\Context\Coins\Coin\Domain\Event;

use App\Context\Coins\Event\CoinsDomainEvent;
use App\SharedKernel\Domain\Bus\Event\DomainEvent;

class CoinWasUpdated extends CoinsDomainEvent
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
        return new self($aggregateId, $body['coin_id'], $body['quantity'],$body['coin_value'], $body['valid_for_change'], $eventId, $occurredOn);
    }

    // It returns the name of the event, not really use, but for the flies....
    public static function eventName(): string
    {
        return 'coin.coin_updated';
    }
}
