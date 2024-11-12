<?php

declare(strict_types=1);

namespace App\Context\Customers\Customer\Domain\ValueObject;

use App\SharedKernel\Domain\ValueObject\JsonValueObject;
use App\SharedKernel\Domain\ValueObject\StringValueObject;
use App\Tests\Unit\Coins\Coin\Domain\CoinMother;
use Faker\Factory;

final class CustomerInsertedMoney extends JsonValueObject {

    public static function randomCoins($elements): self
    {
        return new static(json_encode(CoinMother::createArray($elements)));
    }



}
