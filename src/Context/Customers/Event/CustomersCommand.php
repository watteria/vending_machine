<?php

namespace App\Context\Customers\Event;

use App\Context\Customers\Customer\Domain\ValueObject\CustomerId;
use App\Context\Customers\Customer\Domain\ValueObject\CustomerInsertedMoney;
use App\Context\Customers\Customer\Domain\ValueObject\CustomerStatus;
use App\Context\Items\Item\Domain\ValueObject\ItemId;
use App\SharedKernel\Domain\Bus\Command\Command;


/***
 * Customer command Bus
 */
abstract class CustomersCommand implements Command
{

    public function __construct(private readonly CustomerId $customer_id, private readonly ItemId $id_product, private readonly CustomerInsertedMoney $inserted_money, private readonly CustomerStatus $status, private readonly array $remaining_machine_coins)
    {
    }

    public function id(): CustomerId
    {
        return $this->customer_id;
    }

    public function customer_id(): CustomerId
    {
        return $this->customer_id;
    }
    public function id_product(): ItemId
    {
        return $this->id_product;
    }

    public function inserted_money(): CustomerInsertedMoney
    {
        return $this->inserted_money;
    }
    public function remaining_machine_coins(): array
    {
        return $this->remaining_machine_coins;
    }

    public function status(): CustomerStatus
    {
        return $this->status;
    }


}
