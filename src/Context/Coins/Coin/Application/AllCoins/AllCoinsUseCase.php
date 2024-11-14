<?php

namespace App\Context\Coins\Coin\Application\AllCoins;

use App\Context\Coins\Coin\Domain\Repository\CoinRepository;

class AllCoinsUseCase
{
    public function __construct(private readonly CoinRepository $repository)
    {
    }

    /***
     * Get all coins in the repository
     * @return array|null
     */
    public function __invoke(): ? array
    {
        $coins = $this->repository->searchAll();

        if (empty($coins)) {
            return null;
        }

        return $coins;
    }
}
