<?php

namespace App\SharedKernel\Domain\Aggregate;

use App\SharedKernel\Domain\Bus\Event\DomainEvent;


abstract class AggregateRoot
{
    private array $domainEvents = [];

    /***
     * Domaim Event registration
     * @param DomainEvent $event
     * @return void
     */
    protected function record(DomainEvent $event): void
    {
        $this->domainEvents[] = $event;
    }

    /***
     * Returns all registered domain events and clears the internal list.
     * @return array
     */
    public function pullDomainEvents(): array
    {
        $events = $this->domainEvents;

        $this->domainEvents = [];

        return $events;
    }

}
