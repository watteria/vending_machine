<?php

declare(strict_types=1);

namespace App\Context\Coins\Coin\Domain\ValueObject;

use App\SharedKernel\Domain\ValueObject\IntValueObject;

final class CoinQuantity extends IntValueObject {

    final public static function random($min,$max): self
    {
        return new self(parent::random($min,$max)->value());

    }

}
