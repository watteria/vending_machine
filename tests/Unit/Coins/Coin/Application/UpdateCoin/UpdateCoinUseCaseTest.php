<?php

namespace App\Tests\Unit\Coins\Coin\Application\UpdateCoin;

use App\Context\Coins\Coin\Domain\Coin;
use App\Context\Coins\Coin\Domain\Repository\CoinRepository;
use App\Context\Coins\Coin\Application\UpdateCoin\UpdateCoinUseCase;
use App\Context\Coins\Coin\Domain\Event\CoinWasUpdated;
use App\SharedKernel\Domain\Bus\Event\EventBus;
use App\Tests\Unit\Coins\Coin\Domain\CoinMother;
use App\Tests\Unit\SharedKernel\Domain\Mothers\FloatMother;
use App\Tests\Unit\SharedKernel\Domain\Mothers\IntMother;
use App\Tests\Unit\SharedKernel\UnitTestCase;

class UpdateCoinUseCaseTest extends UnitTestCase
{
    public function test_it_updates_coin(): void
    {
        $repository = $this->createMock(CoinRepository::class);
        $eventBus = $this->createMock(EventBus::class);

        $coin = CoinMother::default();
        $price_value = FloatMother::create();
        $quantity_value = IntMother::create();
        $updatedCoin = new Coin($coin->id(), $quantity_value, $price_value, $coin->valid_for_change());

        $repository
            ->expects(self::once())
            ->method('save')
            ->with($this->callback(function (Coin $savedCoin) use ($price_value, $quantity_value) {
                return $savedCoin->coin_value() === $price_value &&
                    $savedCoin->quantity() === $quantity_value;
            }));

        $eventBus
            ->expects(self::once())
            ->method('publish')
            ->with($this->callback(function ($event) use ($updatedCoin) {
                return $event instanceof CoinWasUpdated &&
                    $event->aggregateId() === $updatedCoin->id() &&
                    $event->coin_id() === $updatedCoin->coin_id() &&
                    $event->quantity() === $updatedCoin->quantity() &&
                    $event->coin_value() === $updatedCoin->coin_value() &&
                    $event->valid_for_change() === $updatedCoin->valid_for_change();
            }));

        $useCase = new UpdateCoinUseCase($repository, $eventBus);
        $useCase->__invoke($updatedCoin->coin_id(), $updatedCoin->quantity(), $updatedCoin->coin_value(), $updatedCoin->valid_for_change());

        $repository
            ->expects(self::once())
            ->method('search')
            ->with($coin->coin_id())
            ->willReturn($updatedCoin);

        $foundCoin = $repository->search($coin->coin_id());
        $this->assertEquals($price_value, $foundCoin->coin_value());
    }

}
