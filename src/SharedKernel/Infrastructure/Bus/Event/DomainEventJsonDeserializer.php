<?php

declare(strict_types=1);

namespace App\SharedKernel\Infrastructure\Bus\Event;



use App\SharedKernel\Domain\Bus\Event\DomainEvent;
use App\SharedKernel\Domain\Utils;

final  class DomainEventJsonDeserializer
{
	public function __construct(private readonly DomainEventMapping $mapping) {}

	public function deserialize(string $domainEvent): DomainEvent
	{
		$eventData = Utils::jsonDecode($domainEvent);
		$eventName = $eventData['data']['type'];
		$eventClass = $this->mapping->for($eventName);

		return $eventClass::fromPrimitives(
			$eventData['data']['attributes']['id'],
			$eventData['data']['attributes'],
			$eventData['data']['id'],
			$eventData['data']['occurred_on']
		);
	}
}
