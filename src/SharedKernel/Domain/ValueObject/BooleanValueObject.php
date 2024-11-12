<?php

declare(strict_types=1);

namespace App\SharedKernel\Domain\ValueObject;

use Faker\Factory;

abstract class BooleanValueObject
{
	public function __construct(protected bool $value) {

    }

	final public function value(): bool
	{

		return $this->value;
	}

     public static function random(): self
    {
        return new static(Factory::create()->boolean());
    }


}
