<?php
namespace App\Context\Coins\Coin\Domain\Tools;
class MoneyChangeOnLimitedCoins
{
    public static function calculateChange($machineCoins, $userCoins, $price): array
    {
        if($userCoins==0){
            return [
                'status' => 'nothing_to_return',
                'change_to_return' => 0,
                'coins_on_machine' =>$machineCoins,
                'machine_total' => MoneyCounterFromJson::calculateTotal($machineCoins)
            ];
        }

        $combinedCoins = self::combineValidCoins($machineCoins, $userCoins);
        $totalUserCoins = MoneyCounterFromJson::calculateTotal($userCoins);
        $changeToReturn = round($totalUserCoins - $price,2);

        if ($totalUserCoins < $price) {
            return [
                'status' => 'insufficient_inserted_coins',
                'change_to_return' => 0,
                'coins_on_machine' => $machineCoins,
                'machine_total' => MoneyCounterFromJson::calculateTotal($machineCoins)
            ];
        }

        $totalMachineCoins = MoneyCounterFromJson::calculateTotal($combinedCoins);

        if ($totalMachineCoins < $changeToReturn) {

            return [
                'status' => 'insufficient_machine_change',
                'change_to_return' => 0,
                'coins_on_machine' => $machineCoins,
                'machine_total' => MoneyCounterFromJson::calculateTotal($machineCoins)
            ];
        }


        $change = self::calculateChangeToReturn($combinedCoins, $changeToReturn);

        if (empty($change)) {
            return [
                'status' => 'nothing_to_return',
                'change_to_return' => 0,
                'coins_on_machine' =>$machineCoins,
                'machine_total' => MoneyCounterFromJson::calculateTotal($machineCoins)
            ];
        }


        $remainingCoins = self::updateMachineCoins($machineCoins, $change);

        return [
            'status' => 'return',
            'change_to_return' => $change,
            'coins_on_machine' => $remainingCoins,
            'machine_total' => MoneyCounterFromJson::calculateTotal($remainingCoins)
        ];
    }

    private static function combineValidCoins($machineCoins, $userCoins)
    {
        $validCoins = [];

        foreach ($machineCoins as $coin) {
            if ($coin['valid_for_change'] == 1) {
                $id = $coin['coin_id'];
                if (isset($validCoins[$id])) {
                    $validCoins[$id]['quantity'] += $coin['quantity'];
                } else {
                    $validCoins[$id] = $coin;
                }
            }
        }

        foreach ($userCoins as $coin) {
            if ($coin['valid_for_change'] == 1) {
                $id = $coin['coin_id'];
                if (isset($validCoins[$id])) {
                    $validCoins[$id]['quantity'] += $coin['quantity'];
                } else {
                    $validCoins[$id] = $coin;
                }
            }
        }


        usort($validCoins, function ($a, $b) {
            return $b['coin_value'] <=> $a['coin_value'];
        });

        return array_values($validCoins);
    }


    private static function calculateChangeToReturn($coins, $amountToReturn)
    {
        $change = [];

        foreach ($coins as $coin) {
            $usedCoins = 0;

            while ($coin['quantity'] > 0 && $coin['coin_value'] <= $amountToReturn) {
                $amountToReturn -= $coin['coin_value'];
                $coin['quantity']--;
                $usedCoins++;
            }

            if ($usedCoins > 0) {
                $change[] = [
                    'coin_id' => $coin['coin_id'],
                    'coin_value' => $coin['coin_value'],
                    'quantity' => $usedCoins
                ];
            }

            if ($amountToReturn <= 0) {
                break;
            }
        }

        if ($amountToReturn > 0) {
            return $change;
        }

        return $change;
    }



    private static function updateMachineCoins($machineCoins, $change)
    {
        foreach ($change as $changeCoin) {
            foreach ($machineCoins as &$machineCoin) {
                if ($machineCoin['coin_id'] === $changeCoin['coin_id']) {
                    $machineCoin['quantity'] -= $changeCoin['quantity'];
                    break;
                }
            }
        }

        return $machineCoins;
    }
}
