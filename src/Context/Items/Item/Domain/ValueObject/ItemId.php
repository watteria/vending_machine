<?php

declare(strict_types=1);

namespace App\Context\Items\Item\Domain\ValueObject;

use App\SharedKernel\Domain\ValueObject\UuidValueObject;

final class ItemId extends UuidValueObject {

    final public static function random(): self
    {
        return new self(parent::random()->value());

    }

}
