<?php

declare(strict_types=1);

namespace App\Context\Coins\Coin\Domain\ValueObject;

use App\SharedKernel\Domain\ValueObject\UuidValueObject;

final class CoinId extends UuidValueObject {

    final public static function random(): self
    {
        return new self(parent::random()->value());

    }

}
