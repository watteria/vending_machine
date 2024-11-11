<?php


namespace App\Tests\Unit\Customers\Customer\Domain;

class StatusMother
{
    private const STATUSES = [
        'PROCESSING',
        'COMPLETED',
        'CANCELLED'
    ];

    public static function create(?string $status = null): string
    {
        return $status ?? self::randomStatus();
    }

    private static function randomStatus(): string
    {
        return self::STATUSES[array_rand(self::STATUSES)];
    }

    public static function default(): string
    {
        return self::create();
    }
}
