<?php

namespace App\Context\Coins\Coin\Domain\Repository;

use App\Context\Coins\Coin\Domain\Coin;

interface CoinRepository
{


    public function searchAll(): array;
    public function search($coin_id): ?Coin;

    public function save(Coin $coin): void;
    public function delete(Coin $coin): void;
}
