<?php

declare(strict_types=1);

namespace App\Context\Items\Item\Domain\ValueObject;

use App\SharedKernel\Domain\ValueObject\StringValueObject;

final class ItemProductName extends StringValueObject {

    /***
     * Random string with a maximum of max_chars characters ( int )
     *
     * @param $max_chars
     * @return self
     */
    final public static function random($max_chars): self
    {
        return new self(parent::random($max_chars)->value());

    }


}
