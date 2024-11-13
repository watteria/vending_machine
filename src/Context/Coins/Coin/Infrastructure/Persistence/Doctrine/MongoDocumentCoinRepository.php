<?php

namespace App\Context\Coins\Coin\Infrastructure\Persistence\Doctrine;

use App\Context\Coins\Coin\Domain\Coin;
use App\Context\Coins\Coin\Domain\Repository\CoinRepository;
use App\Context\Coins\Coin\Domain\ValueObject\CoinId;
use App\Context\Coins\Coin\Domain\ValueObject\CoinQuantity;
use App\Context\Coins\Coin\Domain\ValueObject\CoinValidForChange;
use App\Context\Coins\Coin\Domain\ValueObject\CoinValue;
use App\SharedKernel\Infrastructure\Persistence\Doctrine\MongoRepository;

class MongoDocumentCoinRepository extends MongoRepository  implements CoinRepository
{
    protected function entity(): string
    {
        return Coin::class;
    }

    public function search($coin_id): ?Coin
    {
        $coin = $this->documentManager->getRepository(Coin::class)->findOneBy(['coin_id' => $coin_id]);

        return $coin;
    }

    public function searchAll(): array
    {

        $coins = $this->documentManager->getRepository(Coin::class)->findAll();

        return $coins;
    }

    public function save(Coin $coin): void
    {
        $this->documentManager->persist($coin);
        $this->documentManager->flush();
    }

    public function delete(Coin $coin): void
    {
        $this->documentManager->remove($coin);
        $this->documentManager->flush();
    }

}
