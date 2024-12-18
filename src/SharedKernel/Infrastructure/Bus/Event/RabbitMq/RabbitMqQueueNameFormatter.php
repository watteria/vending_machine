<?php

declare(strict_types=1);


namespace App\SharedKernel\Infrastructure\Bus\Event\RabbitMq;


use App\SharedKernel\Domain\Bus\Event\DomainEventSubscriber;
use App\SharedKernel\Domain\Utils;
use function Lambdish\Phunctional\last;
use function Lambdish\Phunctional\map;

final class RabbitMqQueueNameFormatter
{
	public static function format(DomainEventSubscriber $subscriber): string
	{

		$subscriberClassPaths = explode('\\',  $subscriber::class);

		$queueNameParts = [
			$subscriberClassPaths[2],
			$subscriberClassPaths[3],
			$subscriberClassPaths[4],
			last($subscriberClassPaths),
		];

		return implode('.', map(self::toSnakeCase(), $queueNameParts));
	}

	public static function formatRetry(DomainEventSubscriber $subscriber): string
	{
		$queueName = self::format($subscriber);

		return "retry.$queueName";
	}

	public static function formatDeadLetter(DomainEventSubscriber $subscriber): string
	{
		$queueName = self::format($subscriber);

		return "dead_letter.$queueName";
	}

	public static function shortFormat(DomainEventSubscriber $subscriber): string
	{
		$subscriberCamelCaseName = (string) last(explode('\\', $subscriber::class));

		return Utils::toSnakeCase($subscriberCamelCaseName);
	}

	private static function toSnakeCase(): callable
	{
		return static fn (string $text): string => Utils::toSnakeCase($text);
	}
}
