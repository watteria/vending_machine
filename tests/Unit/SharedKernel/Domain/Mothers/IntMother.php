<?php

declare(strict_types=1);


namespace App\Tests\Unit\SharedKernel\Domain\Mothers;

use Faker\Factory;

final class IntMother
{
	public static function create($max_digits=''): int
	{
        return Factory::create()->randomNumber($max_digits);
	}

}
