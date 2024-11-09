<?php

declare(strict_types=1);


namespace App\SharedKernel\Infrastructure\Symfony;

use App\SharedKernel\Domain\Bus\Command\Command;
use App\SharedKernel\Domain\Bus\Command\CommandBus;
use App\SharedKernel\Domain\Bus\Query\Query;
use App\SharedKernel\Domain\Bus\Query\QueryBus;
use App\SharedKernel\Domain\Bus\Query\Response;
use function Lambdish\Phunctional\each;

abstract class ApiController
{
	public function __construct(
		private readonly QueryBus $queryBus,
		private readonly CommandBus $commandBus,
		ApiExceptionsHttpStatusCodeMapping $exceptionHandler
	) {
		each(
			fn (int $httpCode, string $exceptionClass) => $exceptionHandler->register($exceptionClass, $httpCode),
			$this->exceptions()
		);
	}

	abstract protected function exceptions(): array;

	protected function ask(Query $query): ?Response
	{
		return $this->queryBus->ask($query);
	}

	protected function dispatch(Command $command): void
	{
		$this->commandBus->dispatch($command);
	}
}
