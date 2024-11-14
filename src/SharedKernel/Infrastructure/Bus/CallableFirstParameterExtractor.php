<?php

declare(strict_types=1);

namespace App\SharedKernel\Infrastructure\Bus;

use App\SharedKernel\Domain\Bus\Event\DomainEventSubscriber;
use LogicException;
use ReflectionClass;
use ReflectionMethod;
use ReflectionNamedType;

use function Lambdish\Phunctional\map;
use function Lambdish\Phunctional\reduce;
use function Lambdish\Phunctional\reindex;

/**
 * Class CallableFirstParameterExtractor
 * Extracts the class type of the first parameter from callable handlers, organizing callables based on their
 * expected parameter types, used for not been a "coñazo"  the event dispatching.
 */
final class CallableFirstParameterExtractor
{
    /***
     * Maps each callable to an array where the key is the class type of the first parameter.
     * @param iterable $callables
     * @return array
     */
	public static function forCallables(iterable $callables): array
	{
		return map(self::unflatten(), reindex(self::classExtractor(new self()), $callables));
	}

    /***
     * Reduces the callables into a piped structure for handling multiple subscribers.
     * @param iterable $callables
     * @return array
     */
	public static function forPipedCallables(iterable $callables): array
	{
		return reduce(self::pipedCallablesReducer(), $callables, []);
	}

    /***
     * Extracts the class type of the first parameter for a given handler.
     * @param CallableFirstParameterExtractor $parameterExtractor
     * @return callable
     */
	private static function classExtractor(self $parameterExtractor): callable
	{
		return static fn (object $handler): ?string => $parameterExtractor->extract($handler);
	}

    /***
     * Reduces the callables into an array where each event has a list of subscribers.
     * @return callable
     */
	private static function pipedCallablesReducer(): callable
	{
		return static function (array $subscribers, DomainEventSubscriber $subscriber): array {
			$subscribedEvents = $subscriber::subscribedTo();

			foreach ($subscribedEvents as $subscribedEvent) {
				$subscribers[$subscribedEvent][] = $subscriber;
			}

			return $subscribers;
		};
	}

    /****
     * Unflattens a value, wrapping it in an array.
     * @return callable
     */
	private static function unflatten(): callable
	{
		return static fn (mixed $value): array => [$value];
	}

    /***
     * Extracts the class name of the first parameter of the __invoke method for a given handler class.
     *
     * @param object $class
     * @return string|null
     * @throws \ReflectionException
     */
	public function extract(object $class): ?string
	{
		$reflector = new ReflectionClass($class);
		$method = $reflector->getMethod('__invoke');

		if ($this->hasOnlyOneParameter($method)) {
			return $this->firstParameterClassFrom($method);
		}

		return null;
	}


    /***
     * Retrieves the class name of the first parameter from a method.
     * @param ReflectionMethod $method
     * @return string
     */
	private function firstParameterClassFrom(ReflectionMethod $method): string
	{
		/** @var ReflectionNamedType|null $fistParameterType */
		$fistParameterType = $method->getParameters()[0]->getType();

		if ($fistParameterType === null) {
			throw new LogicException('Missing type hint for the first parameter of __invoke');
		}

		return $fistParameterType->getName();
	}

    /***
     * Checks if a method has only one parameter.
     * @param ReflectionMethod $method
     * @return bool
     */
	private function hasOnlyOneParameter(ReflectionMethod $method): bool
	{
		return $method->getNumberOfParameters() === 1;
	}
}
