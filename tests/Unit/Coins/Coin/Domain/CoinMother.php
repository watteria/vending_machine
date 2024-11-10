<?php

namespace App\Tests\Unit\Coins\Coin\Domain;

use App\Context\Coins\Coin\Domain\Coin;
use App\Tests\Unit\SharedKernel\Domain\Mothers\FloatMother;
use App\Tests\Unit\SharedKernel\Domain\Mothers\IntMother;
use App\Tests\Unit\SharedKernel\Domain\Mothers\UuidMother;

class CoinMother
{


    public static function create(
        ?string $coin_id = null,
        ?int $quantity = null,
        ?float $coin_value = null,
        ?float $valid_for_change = null
    ): Coin {
        return new Coin(
    $coin_id ?? UuidMother::create(),
    $quantity ?? IntMother::create(2),
    $coin_value ?? FloatMother::create(2),
    $valid_for_change ?? rand(0,1)
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
            'coin_id' => $coin->coin_id(),
            'quantity' => $coin->quantity(),
            'coin_value' => $coin->coin_value(),
            'valid_for_change' => $coin->valid_for_change(),
        ];
    }

    public static function default(): Coin
    {
        return self::create();
    }
}