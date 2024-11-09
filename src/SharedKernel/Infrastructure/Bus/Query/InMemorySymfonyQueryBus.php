<?php

declare(strict_types=1);

namespace App\SharedKernel\Infrastructure\Bus\Query;

use App\SharedKernel\Domain\Bus\Query\QueryBus;
use App\SharedKernel\Infrastructure\Bus\CallableFirstParameterExtractor;
use Symfony\Component\Messenger\Exception\NoHandlerForMessageException;
use Symfony\Component\Messenger\Handler\HandlersLocator;
use Symfony\Component\Messenger\MessageBus;
use Symfony\Component\Messenger\Middleware\HandleMessageMiddleware;
use Symfony\Component\Messenger\Stamp\HandledStamp;
use App\SharedKernel\Domain\Bus\Query\Query;
use App\SharedKernel\Domain\Bus\Query\Response;


final  class InMemorySymfonyQueryBus implements QueryBus
{
	private MessageBus $bus;

	public function __construct(iterable $queryHandlers)
	{
		$this->bus = new MessageBus(
			[
				new HandleMessageMiddleware(new HandlersLocator(CallableFirstParameterExtractor::forCallables($queryHandlers))),
			]
		);
	}

	public function ask(Query $query): ?Response
	{
		try {
			/** @var HandledStamp $stamp */
			$stamp = $this->bus->dispatch($query)->last(HandledStamp::class);

			return $stamp->getResult();
		} catch (NoHandlerForMessageException) {
			throw new QueryNotRegisteredError($query);
		}
	}
}
