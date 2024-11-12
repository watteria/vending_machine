<?php

namespace App\Context\Coins\Coin\Application\UpdateCoin;

use App\Context\Coins\Coin\Domain\Repository\CoinRepository;
use App\Context\Coins\Coin\Domain\Coin;
use App\Context\Coins\Coin\Domain\ValueObject\CoinId;
use App\Context\Coins\Coin\Domain\ValueObject\CoinQuantity;
use App\Context\Coins\Coin\Domain\ValueObject\CoinValidForChange;
use App\Context\Coins\Coin\Domain\ValueObject\CoinValue;
use App\SharedKernel\Domain\Bus\Event\EventBus;

class UpdateCoinUseCase
{
    public function __construct(
        private readonly CoinRepository $repository,
        private readonly EventBus $eventBus
    ) {
    }

    public function __invoke(CoinId $coin_id, CoinQuantity $quantity,CoinValue $coin_value,CoinValidForChange $valid_for_change): void
    {
        $coin = Coin::update($coin_id, $quantity,$coin_value,$valid_for_change);

        $this->repository->save($coin);

        $this->eventBus->publish(...$coin->pullDomainEvents());
    }
}
