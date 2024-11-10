<?php

declare(strict_types=1);


namespace App\SharedKernel\Infrastructure\Bus\Event\RabbitMq;



use \App\SharedKernel\Domain\Bus\Event\DomainEvent;
use App\SharedKernel\Domain\Bus\Event\EventBus;
use App\SharedKernel\Infrastructure\Bus\Event\DomainEventJsonSerializer;
use function Lambdish\Phunctional\each;

final  class RabbitMqEventBus implements EventBus
{
	public function __construct(
		private readonly RabbitMqConnection $connection,
		private readonly string $exchangeName
	) {}

	public function publish(DomainEvent ...$events): void
	{
		each($this->publisher(), $events);
	}

	private function publisher(): callable
	{
		return function (DomainEvent $event): void {

				$this->publishEvent($event);

		};
	}

	private function publishEvent(DomainEvent $event): void
	{
		$body = DomainEventJsonSerializer::serialize($event);
		$routingKey = $event::eventName();
		$messageId = $event->eventId();

		$this->connection->exchange($this->exchangeName)->publish(
			$body,
			$routingKey,
			AMQP_NOPARAM,
			[
				'message_id' => $messageId,
				'content_type' => 'application/json',
				'content_encoding' => 'utf-8',
			]
		);
	}
}
