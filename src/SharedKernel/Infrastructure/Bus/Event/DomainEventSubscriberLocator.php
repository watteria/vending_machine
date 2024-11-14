<?php

declare(strict_types=1);


namespace App\SharedKernel\Infrastructure\Bus\Event;

use App\SharedKernel\Domain\Bus\Event\DomainEventSubscriber;
use App\SharedKernel\Infrastructure\Bus\CallableFirstParameterExtractor;
use App\SharedKernel\Infrastructure\Bus\Event\RabbitMq\RabbitMqQueueNameFormatter;
use RuntimeException;
use Traversable;

use function Lambdish\Phunctional\search;

final class DomainEventSubscriberLocator
{
	private readonly array $mapping;

	public function __construct(Traversable $mapping)
	{
		$this->mapping = iterator_to_array($mapping);
	}

    /***
     * Retrieves all subscribers for a specific event class.
     * @param string $eventClass
     * @return array
     */
	public function allSubscribedTo(string $eventClass): array
	{
		$formatted = CallableFirstParameterExtractor::forPipedCallables($this->mapping);

		return $formatted[$eventClass];
	}

    /***
     * Retrieves the subscriber associated with a specific RabbitMQ queue name.
     *
     * @param string $queueName
     * @return callable|DomainEventSubscriber
     */
	public function withRabbitMqQueueNamed(string $queueName): callable | DomainEventSubscriber
	{
		$subscriber = search(
			static fn (DomainEventSubscriber $subscriber): bool => RabbitMqQueueNameFormatter::format($subscriber) ===
															$queueName,
			$this->mapping
		);

		if ($subscriber === null) {
			throw new RuntimeException("There are no subscribers for the <$queueName> queue");
		}

		return $subscriber;
	}

    /***
     * Returns all available event subscribers.
     * @return array
     */
	public function all(): array
	{
		return $this->mapping;
	}
}
