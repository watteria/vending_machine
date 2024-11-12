<?php

declare(strict_types=1);

namespace App\Context\Coins\Coin\Domain\ValueObject;

use App\SharedKernel\Domain\ValueObject\FloatValueObject;

final class CoinValue extends FloatValueObject {


    final public static function random($max_decimals): self
    {
        return new self(parent::random($max_decimals)->value());

    }

}
