<?php

declare(strict_types=1);

namespace App\SharedKernel\Domain\ValueObject;

use Faker\Factory;

abstract class IntValueObject
{
	public function __construct(protected int $value) {}

	final public function value(): int
	{
		return $this->value;
	}
     public static function random($min,$max): self
    {
        return new static(Factory::create()->numberBetween($min,$max));
    }


}
