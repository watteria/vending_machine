<?php

declare(strict_types=1);


namespace App\Tests\Unit\SharedKernel\Domain\Mothers;

use Faker\Factory;

final class FloatMother
{
	public static function create($max_decimals=''): float
	{
        return Factory::create()->randomFloat($max_decimals);
	}

}
