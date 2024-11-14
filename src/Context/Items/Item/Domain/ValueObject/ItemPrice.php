<?php

declare(strict_types=1);

namespace App\Context\Items\Item\Domain\ValueObject;

use App\SharedKernel\Domain\ValueObject\FloatValueObject;

final class ItemPrice extends FloatValueObject {

    /***
     * Random Float value, max_decimals (int)
     * @param $max_decimals
     * @return self
     */
    final public static function random($max_decimals): self
    {
        return new self(parent::random($max_decimals)->value());

    }

}
