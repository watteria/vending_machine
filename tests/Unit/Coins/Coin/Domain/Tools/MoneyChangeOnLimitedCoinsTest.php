<?php
namespace App\Tests\Unit\Coins\Coin\Domain\Tools;

use App\Context\Coins\Coin\Domain\Tools\MoneyChangeOnLimitedCoins;
use PHPUnit\Framework\TestCase;
use App\Tests\Unit\Coins\Coin\Domain\CoinMother;
use App\Tests\Unit\SharedKernel\UnitTestCase;

class MoneyChangeOnLimitedCoinsTest extends TestCase
{

    public function test_change_with_insufficient_customer_coins()
    {
        $userCoins = [
            CoinMother::toArray(CoinMother::create(quantity: 5, coin_value: 1.0, valid_for_change: 1)),
            CoinMother::toArray(CoinMother::create(quantity: 3, coin_value: 2.0, valid_for_change: 1)),
        ];

        $machineCoins = [
            CoinMother::toArray(CoinMother::create(quantity: 10, coin_value: 1.0, valid_for_change: 1)),
            CoinMother::toArray(CoinMother::create(quantity: 5, coin_value: 2.0, valid_for_change: 1)),
        ];

        $result = MoneyChangeOnLimitedCoins::calculateChange($machineCoins,$userCoins, 50);
        $expected_coins_to_return = 0;

        $expected_coins_on_machine = [
            ['quantity' => 10, 'coin_value' => 1.0, 'valid_for_change' => 1],
            ['quantity' => 5, 'coin_value' => 2.0, 'valid_for_change' => 1]
        ];

        $actual_coins_on_machine = array_map(function ($coin) {
            return [
                'quantity' => $coin['quantity'],
                'coin_value' => $coin['coin_value'],
                'valid_for_change' => $coin['valid_for_change']
            ];
        }, $result['coins_on_machine']);


        $this->assertEquals('insufficient_inserted_coins', $result['status'], 'Change should fail due to insufficient inserted coins.');
        $this->assertEquals($expected_coins_to_return, $result['change_to_return'], 'The change amount should be correct.');
        $this->assertEquals($expected_coins_on_machine, $actual_coins_on_machine, 'The coins on machine should be correct.');
        $this->assertEquals(20, $result['machine_total'], 'The machine total should be correct.');
    }


    public function test_change_with_sufficient_customer_coins()
    {
        $userCoins = [
            CoinMother::toArray(CoinMother::create(quantity: 5, coin_value: 1.0, valid_for_change: 1)),
            CoinMother::toArray(CoinMother::create(quantity: 3, coin_value: 2.0, valid_for_change: 1)),
        ];

        $machineCoins = [
            CoinMother::toArray(CoinMother::create(quantity: 10, coin_value: 1.0, valid_for_change: 1)),
            CoinMother::toArray(CoinMother::create(quantity: 5, coin_value: 2.0, valid_for_change: 0)),
        ];

        $result = MoneyChangeOnLimitedCoins::calculateChange($machineCoins,$userCoins, 10);

        $expected_coins_to_return = [
            ['coin_value' => 1.0, 'quantity' => 1]
        ];
        $expected_coins_on_machine = [
            ['quantity' => 9, 'coin_value' => 1.0, 'valid_for_change' => 1],
            ['quantity' => 5, 'coin_value' => 2.0, 'valid_for_change' => 0]
        ];


        $actual_coins_to_return = array_map(function ($coin) {
            return [
                'coin_value' => $coin['coin_value'],
                'quantity' => $coin['quantity']
            ];
        }, $result['change_to_return']);


        $actual_coins_on_machine = array_map(function ($coin) {
            return [
                'quantity' => $coin['quantity'],
                'coin_value' => $coin['coin_value'],
                'valid_for_change' => $coin['valid_for_change']
            ];
        }, $result['coins_on_machine']);

        $this->assertEquals('return', $result['status'], 'Change should be successful.');
        $this->assertEquals($expected_coins_to_return, $actual_coins_to_return, 'The coins to return should be correct.');
        $this->assertEquals($expected_coins_on_machine, $actual_coins_on_machine, 'The coins on machine should be correct.');
        $this->assertEquals(19, $result['machine_total'], 'The machine total should be correct.');
    }


    public function test_change_with_insufficient_machine_coins()
    {
        $userCoins = [
            CoinMother::toArray(CoinMother::create(quantity: 50, coin_value: 1.0, valid_for_change: 0)),
            CoinMother::toArray(CoinMother::create(quantity: 3, coin_value: 2.0, valid_for_change: 0)),
        ];

        $machineCoins = [
            CoinMother::toArray(CoinMother::create(quantity: 1, coin_value: 0.5, valid_for_change: 1)),
            CoinMother::toArray(CoinMother::create(quantity: 2, coin_value: 2.0, valid_for_change: 0)),
        ];

        $result = MoneyChangeOnLimitedCoins::calculateChange( $machineCoins,$userCoins, 1.75);
        $expected_coins_to_return = 0;

        $expected_coins_on_machine = [
            ['quantity' => 1, 'coin_value' => 0.5, 'valid_for_change' => 1],
            ['quantity' => 2, 'coin_value' => 2.0, 'valid_for_change' => 0]
        ];

        $actual_coins_on_machine = array_map(function ($coin) {
            return [
                'quantity' => $coin['quantity'],
                'coin_value' => $coin['coin_value'],
                'valid_for_change' => $coin['valid_for_change']
            ];
        }, $result['coins_on_machine']);


        $this->assertEquals('insufficient_machine_change', $result['status'], 'Change should fail due to insufficient inserted coins.');
        $this->assertEquals($expected_coins_to_return, $result['change_to_return'], 'The change amount should be correct.');
        $this->assertEquals($expected_coins_on_machine, $actual_coins_on_machine, 'The coins on machine should be correct.');
        $this->assertEquals(4.5, $result['machine_total'], 'The machine total should be correct.');
    }


    public function test_change_nothing_to_return()
    {
        $userCoins = [
            CoinMother::toArray(CoinMother::create(quantity: 1, coin_value: 2.0, valid_for_change: 1)),
        ];

        $machineCoins = [
            CoinMother::toArray(CoinMother::create(quantity: 10, coin_value: 1.0, valid_for_change: 1)),
            CoinMother::toArray(CoinMother::create(quantity: 2, coin_value: 2.0, valid_for_change: 1)),
        ];

        $result = MoneyChangeOnLimitedCoins::calculateChange($machineCoins,$userCoins, 2.0);
        $expected_coins_to_return = 0;

        $expected_coins_on_machine = [
            ['quantity' => 10, 'coin_value' => 1.0, 'valid_for_change' => 1],
            ['quantity' => 2, 'coin_value' => 2.0, 'valid_for_change' => 1]
        ];

        $actual_coins_on_machine = array_map(function ($coin) {
            return [
                'quantity' => $coin['quantity'],
                'coin_value' => $coin['coin_value'],
                'valid_for_change' => $coin['valid_for_change']
            ];
        }, $result['coins_on_machine']);

        $this->assertEquals('nothing_to_return', $result['status'], 'Change should fail due to insufficient inserted coins.');
        $this->assertEquals($expected_coins_to_return, $result['change_to_return'], 'The change amount should be correct.');
        $this->assertEquals($expected_coins_on_machine, $actual_coins_on_machine, 'The coins on machine should be correct.');
        $this->assertEquals(14, $result['machine_total'], 'The machine total should be correct.');
    }

}
