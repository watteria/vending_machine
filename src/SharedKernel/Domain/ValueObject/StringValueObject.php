<?php

declare(strict_types=1);

namespace App\SharedKernel\Domain\ValueObject;

use Faker\Factory;

abstract class StringValueObject
{
	public function __construct(protected string $value) {}

	final public function value(): string
	{
		return $this->value;
	}



     public static function random($max_chars): self
    {
        return new static(Factory::create()->text($max_chars));
    }


    public static function randomElement($elementos): self
    {
        return new static(Factory::create()->randomElement($elementos));
    }


}
