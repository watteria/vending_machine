<?php

namespace App\Context\Coins\Coin\Application\UpdateCoin;

use App\Context\Coins\Coin\Domain\Repository\CoinRepository;
use App\Context\Coins\Coin\Domain\Coin;
use App\SharedKernel\Domain\Bus\Event\EventBus;

class UpdateCoinUseCase
{
    public function __construct(
        private readonly CoinRepository $repository,
        private readonly EventBus $eventBus
    ) {
    }

    public function __invoke(string $coin_id, int $quantity,float $coin_value,int $valid_for_change): void
    {
        $coin = Coin::update($coin_id, $quantity,$coin_value,$valid_for_change);

        $this->repository->save($coin);

        $this->eventBus->publish(...$coin->pullDomainEvents());
    }
}
