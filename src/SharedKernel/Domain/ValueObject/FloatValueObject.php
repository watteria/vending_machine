<?php

declare(strict_types=1);

namespace App\SharedKernel\Domain\ValueObject;

use Faker\Factory;

abstract class FloatValueObject
{
	public function __construct(protected float $value) {}

	final public function value(): float
	{
		return $this->value;
	}

     public static function random($max_decimals): self
    {
        return new static(Factory::create()->randomFloat($max_decimals));
    }

    final public function isBiggerThan(self $other): bool
	{
		return $this->value() > $other->value();
	}


}
