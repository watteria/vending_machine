<?php
namespace App\Context\Coins\Coin\Domain\Tools;
class MoneyCounterFromJson
{

    /***
     * Calculate the total euro amount from an array of coins
     *
     * @param $coins
     * @return float
     */
    public static function calculateTotal($coins)
    {

        $total = 0;
        foreach ($coins as $item) {
            $total += $item['coin_value'] * $item['quantity'];
        }
        return round($total,2);
    }
}
