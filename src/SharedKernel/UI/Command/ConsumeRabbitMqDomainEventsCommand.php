<?php

declare(strict_types=1);

namespace App\SharedKernel\UI\Command;

use App\SharedKernel\Infrastructure\Bus\Event\DomainEventSubscriberLocator;
use App\SharedKernel\Infrastructure\Bus\Event\RabbitMq\RabbitMqDomainEventsConsumer;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

use function Lambdish\Phunctional\repeat;

#[AsCommand(
	name: 'oriol:domain-events:rabbitmq:consume',
	description: 'Consume domain events from the RabbitMQ',
)]
final class ConsumeRabbitMqDomainEventsCommand extends Command
{
	public function __construct(
		private readonly RabbitMqDomainEventsConsumer $consumer,
		private readonly DomainEventSubscriberLocator $locator
	) {
		parent::__construct();
	}

	protected function configure(): void
	{
		$this
			->addArgument('queue', InputArgument::REQUIRED, 'Queue name')
			->addArgument('quantity', InputArgument::REQUIRED, 'Quantity of events to process');
	}

	protected function execute(InputInterface $input, OutputInterface $output): int
	{
		$queueName = $input->getArgument('queue');
		$eventsToProcess = (int) $input->getArgument('quantity');

		repeat($this->consumer($queueName), $eventsToProcess);

		return 0;
	}

	private function consumer(string $queueName): callable
	{
		return function () use ($queueName): void {
			$subscriber = $this->locator->withRabbitMqQueueNamed($queueName);

			$this->consumer->consume($subscriber, $queueName);

		};
	}
}
