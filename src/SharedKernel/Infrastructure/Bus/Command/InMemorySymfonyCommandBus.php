<?php

declare(strict_types=1);

namespace App\SharedKernel\Infrastructure\Bus\Command;

use App\SharedKernel\Domain\Bus\Command\CommandBus;
use App\SharedKernel\Domain\Bus\Command\Command;
use App\SharedKernel\Infrastructure\Bus\CallableFirstParameterExtractor;
use Symfony\Component\Messenger\Exception\HandlerFailedException;
use Symfony\Component\Messenger\Exception\NoHandlerForMessageException;
use Symfony\Component\Messenger\Handler\HandlersLocator;
use Symfony\Component\Messenger\MessageBus;
use Symfony\Component\Messenger\Middleware\HandleMessageMiddleware;

final class InMemorySymfonyCommandBus implements CommandBus
{
	private readonly MessageBus $bus;

	public function __construct(iterable $commandHandlers)
	{
		$this->bus = new MessageBus(
			[
				new HandleMessageMiddleware(
					new HandlersLocator(CallableFirstParameterExtractor::forCallables($commandHandlers))
				),
			]
		);
	}

    /***
     * Dispatch to command bus using Symfony message component
     * @param Command $command
     * @return void
     * @throws \Throwable
     */
	public function dispatch(Command $command): void
	{
		try {
			$this->bus->dispatch($command);
		} catch (NoHandlerForMessageException) {
			throw new CommandNotRegisteredError($command);
		} catch (HandlerFailedException $error) {
			throw $error->getPrevious() ?? $error;
		}
	}
}
