<?php

declare(strict_types=1);


namespace App\Tests\Unit\SharedKernel\Domain\Mothers;



use Faker\Factory;

final class StringMother
{
	public static function create($max_words=''): string
	{
		return Factory::create()->sentence($max_words);
	}

}
