<?php

declare(strict_types=1);

namespace App\Tests\Unit\SharedKernel\Domain\Mothers;


use Faker\Factory;

final class UuidMother
{

    public static function create(): string
    {
        return Factory::create()->unique()->uuid();
    }
}
