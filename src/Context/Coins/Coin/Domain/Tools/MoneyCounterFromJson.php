<?php
namespace App\Context\Coins\Coin\Domain\Tools;
class MoneyCounterFromJson
{

    public static function calculateTotal($coins)
    {

        $total = 0;
        foreach ($coins as $item) {
            $total += $item['coin_value'] * $item['quantity'];
        }
        return round($total,2);
    }
}
