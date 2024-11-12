<?php

namespace App\Tests\Unit\Coins\Coin\Application\UpdateCoin;

use App\Context\Coins\Coin\Domain\Coin;
use App\Context\Coins\Coin\Domain\Repository\CoinRepository;
use App\Context\Coins\Coin\Application\UpdateCoin\UpdateCoinUseCase;
use App\Context\Coins\Coin\Domain\Event\CoinWasUpdated;
use App\Context\Coins\Coin\Domain\ValueObject\CoinQuantity;
use App\Context\Coins\Coin\Domain\ValueObject\CoinValue;
use App\SharedKernel\Domain\Bus\Event\EventBus;
use App\Tests\Unit\Coins\Coin\Domain\CoinMother;
use App\Tests\Unit\SharedKernel\UnitTestCase;

class UpdateCoinUseCaseTest extends UnitTestCase
{
    public function test_it_updates_coin(): void
    {
        $repository = $this->createMock(CoinRepository::class);
        $eventBus = $this->createMock(EventBus::class);

        $coin = CoinMother::default();
        $price_value =  CoinValue::random(2);
        $quantity_value = CoinQuantity::random(0,10);
        $updatedCoin = new Coin($coin->id(), $quantity_value, $price_value, $coin->valid_for_change());

        $repository
            ->expects(self::once())
            ->method('save')
            ->with($this->callback(function (Coin $savedCoin) use ($price_value, $quantity_value) {
                return $savedCoin->coin_value()->value() === $price_value->value() &&
                    $savedCoin->quantity()->value() === $quantity_value->value();
            }));

        $eventBus
            ->expects(self::once())
            ->method('publish')
            ->with($this->callback(function ($event) use ($updatedCoin) {
                return $event instanceof CoinWasUpdated &&
                    $event->aggregateId() === $updatedCoin->id()->value() &&
                    $event->coin_id()->value() === $updatedCoin->coin_id()->value() &&
                    $event->quantity()->value() === $updatedCoin->quantity()->value() &&
                    $event->coin_value()->value() === $updatedCoin->coin_value()->value() &&
                    $event->valid_for_change()->value() === $updatedCoin->valid_for_change()->value();
            }));

        $useCase = new UpdateCoinUseCase($repository, $eventBus);
        $useCase->__invoke($updatedCoin->coin_id(), $updatedCoin->quantity(), $updatedCoin->coin_value(), $updatedCoin->valid_for_change());

        $repository
            ->expects(self::once())
            ->method('search')
            ->with($coin->coin_id()->value())
            ->willReturn($updatedCoin);

        $foundCoin = $repository->search($coin->coin_id()->value());
        $this->assertEquals($price_value->value(), $foundCoin->coin_value()->value());
    }

}
