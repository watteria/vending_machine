<?php

namespace App\Tests\Unit\Customers\Customer\Domain;

use App\Context\Customers\Customer\Domain\Customer;
use App\Context\Customers\Customer\Domain\ValueObject\CustomerId;
use App\Context\Customers\Customer\Domain\ValueObject\CustomerInsertedMoney;
use App\Context\Customers\Customer\Domain\ValueObject\CustomerStatus;
use App\Context\Items\Item\Domain\ValueObject\ItemId;
use App\Tests\Unit\Coins\Coin\Domain\CoinMother;

class CustomerMother
{

    public static function create(
        ?CustomerId  $customer_id = null,
        ?ItemId  $id_product = null,
        ?CustomerInsertedMoney  $inserted_money = null,
        ?CustomerStatus  $status = null,
        ?array $remaining_machine_coins = null
    ): Customer {
        return new Customer(
            $customer_id ?? CustomerId::random(),
            $id_product ?? ItemId::random(),
            $inserted_money ?? CustomerInsertedMoney::randomCoins(3),
            $status ?? CustomerStatus::randomStatus(),
$remaining_machine_coins ??  CoinMother::createArray(3),
        );
    }

    public static function default(): Customer
    {
        return self::create();
    }
}