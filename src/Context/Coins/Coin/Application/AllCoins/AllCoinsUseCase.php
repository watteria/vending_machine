<?php

namespace App\Context\Coins\Coin\Application\AllCoins;

use App\Context\Coins\Coin\Domain\Repository\CoinRepository;

class AllCoinsUseCase
{
    public function __construct(private readonly CoinRepository $repository)
    {
    }

    public function __invoke(): ? array
    {
        $coins = $this->repository->searchAll();

        if (empty($coins)) {
            return null;
        }

        return $coins;
    }
}
