<?php

declare(strict_types=1);

namespace App\Context\Items\Item\Domain\ValueObject;

use App\SharedKernel\Domain\ValueObject\IntValueObject;

final class ItemQuantity extends IntValueObject {

    /***
     * random value from min_value to max_vale ( int )
     * @param $min
     * @param $max
     * @return self
     */
    final public static function random($min,$max): self
    {
        return new self(parent::random($min,$max)->value());

    }

}
