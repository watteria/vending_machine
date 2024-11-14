<?php

declare(strict_types=1);


namespace App\SharedKernel\Infrastructure\Bus\Event;


use App\SharedKernel\Domain\Bus\Event\DomainEvent;

final class DomainEventJsonSerializer
{
    /***
     * Serialize message to rabbit
     * @param DomainEvent $domainEvent
     * @return string
     */
	public static function serialize(DomainEvent $domainEvent): string
	{
		return json_encode(
			[
				'data' => [
					'id' => $domainEvent->eventId(),
					'type' => $domainEvent::eventName(),
					'occurred_on' => $domainEvent->occurredOn(),
					'attributes' => array_merge($domainEvent->toPrimitives(), ['id' => $domainEvent->aggregateId()]),
				],
				'meta' => [],
			]
		);
	}
}
