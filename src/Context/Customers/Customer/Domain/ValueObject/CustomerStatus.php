<?php

declare(strict_types=1);

namespace App\Context\Customers\Customer\Domain\ValueObject;

use App\SharedKernel\Domain\ValueObject\StringValueObject;

final class CustomerStatus extends StringValueObject {

    /***
     * Gets randomly value from 'IN_PROCESS','CANCELLED','COMPLETED'
     * @return self
     */
    final public static function randomStatus(): self
    {
        return new self(parent::randomElement(['IN_PROCESS','CANCELLED','COMPLETED'])->value());

    }


}
