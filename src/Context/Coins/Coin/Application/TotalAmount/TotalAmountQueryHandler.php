<?php

namespace App\Context\Coins\Coin\Application\TotalAmount;

use App\Context\Coins\Coin\Domain\Coin;
use App\SharedKernel\Domain\Aggregate\Exception\AggregateNotFoundException;
use App\SharedKernel\Domain\Bus\Query\QueryHandler;
use App\SharedKernel\Domain\Bus\Query\Response;

class TotalAmountQueryHandler implements QueryHandler
{
    public function __construct(private readonly TotalAmountUseCase $useCase)
    {
    }

    public function __invoke(TotalAmountQuery $query): Response
    {
        $coins = $this->useCase->__invoke();
        if (null === $coins) {
            throw AggregateNotFoundException::fromAggregate(Coin::class);
        }

        return new TotalAmountResponse($coins);
    }
}
