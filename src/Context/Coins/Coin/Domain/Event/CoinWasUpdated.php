<?php

namespace App\Context\Coins\Coin\Domain\Event;

use App\Context\Coins\Event\CoinsDomainEvent;
use App\SharedKernel\Domain\Bus\Event\DomainEvent;

class CoinWasUpdated extends CoinsDomainEvent
{

    public static function fromPrimitives(
        string $aggregateId,
        array $body,
        string $eventId,
        string $occurredOn
    ): DomainEvent
    {
        return new self($aggregateId, $body['coin_id'], $body['quantity'],$body['coin_value'], $body['valid_for_change'], $eventId, $occurredOn);
    }

    public static function eventName(): string
    {
        return 'coin.coin_updated';
    }
}
