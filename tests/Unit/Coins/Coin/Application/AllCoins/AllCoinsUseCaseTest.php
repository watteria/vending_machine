<?php

namespace App\Tests\Unit\Coins\Coin\Application\AllCoins;

use App\Context\Coins\Coin\Application\AllCoins\AllCoinsUseCase;
use App\Context\Coins\Coin\Domain\Repository\CoinRepository;
use App\Tests\Unit\Coins\Coin\Domain\CoinMother;
use App\Tests\Unit\SharedKernel\UnitTestCase;

class AllCoinsUseCaseTest extends UnitTestCase
{
    public function test_it_returns_all_coins(): void
    {
        $repository = $this->createMock(CoinRepository::class);
        $coin1 = CoinMother::default();
        $coin2 = CoinMother::default();

        $coins = [$coin1, $coin2];

        $repository
            ->expects(self::once())
            ->method('searchAll')
            ->willReturn($coins);

        $useCase = new AllCoinsUseCase($repository);
        $result = $useCase->__invoke();

        $this->assertEquals($coins, $result);
    }

    public function test_it_returns_null_when_no_coins_found(): void
    {
        $repository = $this->createMock(CoinRepository::class);

        $repository
            ->expects(self::once())
            ->method('searchAll')
            ->willReturn([]);


        $useCase = new AllCoinsUseCase($repository);
        $result = $useCase->__invoke();


        $this->assertNull($result);
    }
}
