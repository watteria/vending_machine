<?php

declare(strict_types=1);

namespace App\Context\Coins\Coin\Domain\ValueObject;

use App\SharedKernel\Domain\ValueObject\BooleanValueObject;

final class CoinValidForChange extends BooleanValueObject {

    final public static function random(): self
    {
        return new self(parent::random()->value());

    }


}
