<?php

declare(strict_types=1);


namespace App\SharedKernel\Infrastructure\Symfony;

use App\SharedKernel\Domain\DomainError;
use App\SharedKernel\Domain\Utils;
use ReflectionClass;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;
use Throwable;

final  class ApiExceptionListener
{
	public function __construct(private readonly  ApiExceptionsHttpStatusCodeMapping $exceptionHandler) {}

	public function onException(ExceptionEvent $event): void
	{
		$exception = $event->getThrowable();

		$event->setResponse(
			new JsonResponse(
				[
					'code' => $this->exceptionCodeFor($exception),
					'message' => $exception->getMessage(),
				],
				$this->exceptionHandler->statusCodeFor($exception::class)
			)
		);
	}

	private function exceptionCodeFor(Throwable $error): string
	{
		$domainErrorClass = DomainError::class;

		return $error instanceof $domainErrorClass
			? $error->errorCode()
			: Utils::toSnakeCase($this->extractClassName($error));
	}

	private function extractClassName(object $object): string
	{
		$reflect = new ReflectionClass($object);

		return $reflect->getShortName();
	}
}
