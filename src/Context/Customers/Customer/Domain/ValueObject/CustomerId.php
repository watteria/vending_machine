<?php

declare(strict_types=1);

namespace App\Context\Customers\Customer\Domain\ValueObject;

use App\SharedKernel\Domain\ValueObject\UuidValueObject;

final class CustomerId extends UuidValueObject {

    final public static function random(): self
    {
        return new self(parent::random()->value());

    }

}
