<?php

namespace App\Context\Coins\Coin\Application\AllCoins;

use App\Context\Coins\Coin\Domain\Coin;
use App\SharedKernel\Domain\Aggregate\Exception\AggregateNotFoundException;
use App\SharedKernel\Domain\Bus\Query\QueryHandler;
use App\SharedKernel\Domain\Bus\Query\Response;

class AllCoinsQueryHandler implements QueryHandler
{
    public function __construct(private readonly AllCoinsUseCase $useCase)
    {
    }

    public function __invoke(AllCoinsQuery $query): Response
    {
        $coins = $this->useCase->__invoke();
        if (null === $coins) {
            throw AggregateNotFoundException::fromAggregate(Coin::class);
        }

        return new AllCoinsResponse($coins);
    }
}
