<?php

namespace App\Tests\Unit\Coins\Coin\Domain\Tools;

use App\Context\Coins\Coin\Domain\Tools\MoneyCounterFromJson;
use PHPUnit\Framework\TestCase;

class MoneyCounterFromJsonTest extends TestCase
{
    public function test_calculate_total_with_multiple_coins(): void
    {
        $coins = [
            ['coin_value' => 1.0, 'quantity' => 5],
            ['coin_value' => 0.5, 'quantity' => 4],
            ['coin_value' => 0.25, 'quantity' => 8],
        ];

        $expectedTotal = 9.0;

        $result = MoneyCounterFromJson::calculateTotal($coins);

        $this->assertEquals($expectedTotal, $result, 'The calculated total should match the expected total.');
    }

    public function test_calculate_total_with_empty_coins(): void
    {
        $coins = [];

        $expectedTotal = 0.0;

        $result = MoneyCounterFromJson::calculateTotal($coins);

        $this->assertEquals($expectedTotal, $result, 'The calculated total should be 0 for an empty array.');
    }

    public function test_calculate_total_with_decimal_values(): void
    {
        $coins = [
            ['coin_value' => 0.1, 'quantity' => 3], // Total: 0.3
            ['coin_value' => 0.2, 'quantity' => 4], // Total: 0.8
        ];

        $expectedTotal = 1.1;

        $result = MoneyCounterFromJson::calculateTotal($coins);

        $this->assertEquals($expectedTotal, $result, 'The calculated total should handle decimal values correctly.');
    }
}
