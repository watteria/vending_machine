<?php

declare(strict_types=1);

namespace App\SharedKernel\Domain\Bus\Event;

use App\SharedKernel\Domain\Utils;
use DateTimeImmutable;
use Ramsey\Uuid\Uuid;

abstract class DomainEvent
{
    private readonly string $eventId;
    private readonly string $occurredOn;

    public function __construct(private readonly string $aggregateId, string $eventId = null, string $occurredOn = null)
    {
        $this->eventId = $eventId ?: Uuid::uuid4()->toString();
        $this->occurredOn = $occurredOn ?: Utils::dateToString(new DateTimeImmutable());
    }

    /***
     * Read the data in the message in order to recreate the instance
     * @param string $aggregateId
     * @param array $body
     * @param string $eventId
     * @param string $occurredOn
     * @return self
     */
    abstract public static function fromPrimitives(
        string $aggregateId,
        array $body,
        string $eventId,
        string $occurredOn
    ): self;

    abstract public static function eventName(): string;

    /***
     * Helps to write domain event body
     * @return array
     */
    abstract public function toPrimitives(): array;

    final public function aggregateId(): string
    {
        return $this->aggregateId;
    }

    final public function eventId(): string
    {
        return $this->eventId;
    }

    final public function occurredOn(): string
    {
        return $this->occurredOn;
    }
}
