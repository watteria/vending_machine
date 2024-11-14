<?php

    declare(strict_types=1);


    namespace App\SharedKernel\Infrastructure\Bus\Event;

    use App\SharedKernel\Domain\Bus\Event\DomainEventSubscriber;
    use RuntimeException;

    use function Lambdish\Phunctional\reduce;
    use function Lambdish\Phunctional\reindex;

    final class DomainEventMapping
    {
        private array $mapping;

        public function __construct(iterable $mapping)
        {
            $this->mapping = reduce($this->eventsExtractor(), $mapping, []);
        }

        /***
         * Retrieves the class name for a given event name.
         * @param string $name
         * @return string
         */
        public function for(string $name): string
        {
            if (!isset($this->mapping[$name])) {
                throw new RuntimeException("The Domain Event Class for <$name> doesn't exists or have no subscribers");
            }

            return $this->mapping[$name];
        }

        /***
         * Provides a callable that extracts event names and maps them to their class names.
         * @return callable
         */
        private function eventsExtractor(): callable
        {
            return fn (array $mapping, DomainEventSubscriber $subscriber): array => array_merge(
                $mapping,
                reindex($this->eventNameExtractor(), $subscriber::subscribedTo())
            );
        }

        /***
         * Provides a callable to extract the event name from a given event class.
         * @return callable
         */
        private function eventNameExtractor(): callable
        {
            return static fn (string $eventClass): string => $eventClass::eventName();
        }
    }
