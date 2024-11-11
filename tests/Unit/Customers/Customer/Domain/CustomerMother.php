<?php

namespace App\Tests\Unit\Customers\Customer\Domain;

use App\Context\Customers\Customer\Domain\Customer;
use App\Tests\Unit\Coins\Coin\Domain\CoinMother;
use App\Tests\Unit\Items\Item\Domain\ItemMother;
use App\Tests\Unit\SharedKernel\Domain\Mothers\UuidMother;

class CustomerMother
{

    public static function create(
        ?string $customer_id = null,
        ?string $id_product = null,
        ?float $inserted_money = null,
        ?string $status = null
    ): Customer {
        return new Customer(
            $customer_id ?? UuidMother::create(),
            $id_product ?? UuidMother::create(),
            $inserted_money ?? json_encode(CoinMother::createArray(3)),
            $status ?? StatusMother::create(),
$remaining_machine_coins ?? json_encode(CoinMother::createArray(3)),
        );
    }

    public static function default(): Customer
    {
        return self::create();
    }
}