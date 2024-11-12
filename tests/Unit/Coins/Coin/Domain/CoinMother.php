<?php

namespace App\Tests\Unit\Coins\Coin\Domain;

use App\Context\Coins\Coin\Domain\Coin;
use App\Context\Coins\Coin\Domain\ValueObject\CoinId;
use App\Context\Coins\Coin\Domain\ValueObject\CoinQuantity;
use App\Context\Coins\Coin\Domain\ValueObject\CoinValidForChange;
use App\Context\Coins\Coin\Domain\ValueObject\CoinValue;
use App\Tests\Unit\SharedKernel\Domain\Mothers\FloatMother;
use App\Tests\Unit\SharedKernel\Domain\Mothers\IntMother;
use App\Tests\Unit\SharedKernel\Domain\Mothers\UuidMother;

class CoinMother
{


    public static function create(
        ?CoinId  $coin_id = null,
        ?CoinQuantity  $quantity = null,
        ?CoinValue  $coin_value = null,
        ?CoinValidForChange  $valid_for_change = null
    ): Coin {
        return new Coin(
    $coin_id ?? CoinId::random(),
    $quantity ?? CoinQuantity::random(0,10),
    $coin_value ??  CoinValue::random(2),
    $valid_for_change ?? CoinValidForChange::random()
        );
    }

    public static function createArray(int $count = 3): array
    {
        $coins = [];
        for ($i = 0; $i < $count; $i++) {
            $coins[] = self::create();
        }
        return $coins;
    }

    public static function toArray(Coin $coin): array
    {
        return [
            'coin_id' => $coin->coin_id()->value(),
            'quantity' => $coin->quantity()->value(),
            'coin_value' => $coin->coin_value()->value(),
            'valid_for_change' => $coin->valid_for_change()->value(),
        ];
    }

    public static function default(): Coin
    {
        return self::create();
    }
}