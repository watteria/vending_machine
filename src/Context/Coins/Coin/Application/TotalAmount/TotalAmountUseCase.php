<?php

namespace App\Context\Coins\Coin\Application\TotalAmount;

use App\Context\Coins\Coin\Domain\Repository\CoinRepository;

class TotalAmountUseCase
{
    public function __construct(private readonly CoinRepository $repository)
    {
    }

    /***
     * Get all coins in the repository in order to get the Total amount in the vendingMachine
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
