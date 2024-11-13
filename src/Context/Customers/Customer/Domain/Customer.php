<?php

namespace App\Context\Customers\Customer\Domain;

use App\Context\Customers\Customer\Domain\Event\CustomerWasCheckout;
use App\Context\Customers\Customer\Domain\Event\CustomerWasCreated;
use App\Context\Customers\Customer\Domain\Event\CustomerWasReset;
use App\Context\Customers\Customer\Domain\Event\CustomerWasUpdated;
use App\Context\Customers\Customer\Domain\ValueObject\CustomerId;
use App\Context\Customers\Customer\Domain\ValueObject\CustomerInsertedMoney;
use App\Context\Customers\Customer\Domain\ValueObject\CustomerStatus;
use App\Context\Items\Item\Domain\ValueObject\ItemId;
use App\SharedKernel\Domain\Aggregate\AggregateRoot;

#[ODM\Document(collection: "customers")]
class Customer extends AggregateRoot
{

    #[ODM\Id(strategy: 'NONE', type: 'string')]
    private string $_id;

    #[ODM\Field(type: 'customer_id')]
    private  CustomerId $customer_id;

    #[ODM\Field(type: 'item_id')]
    private  ItemId $id_product;

    #[ODM\Field(type: 'inserted_money')]
    private  CustomerInsertedMoney $inserted_money;

    #[ODM\Field(type: 'customer_status')]
    private  CustomerStatus $status;
    private array $remaining_machine_coins;

    public function __construct(CustomerId $customer_id, ItemId $id_product, CustomerInsertedMoney $inserted_money, CustomerStatus $status, array $remaining_machine_coins)
    {
        $this->_id = $customer_id;
        $this->customer_id = $customer_id;
        $this->id_product = $id_product;
        $this->inserted_money = $inserted_money;
        $this->status = $status;
        $this->remaining_machine_coins = $remaining_machine_coins;
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


    public static function create(CustomerId $customer_id, ItemId $id_product,CustomerInsertedMoney $inserted_money,CustomerStatus $status,array $remaining_machine_coins): self
    {
        $customer = new self($customer_id, $id_product,$inserted_money,$status,$remaining_machine_coins);

        $customer->record(new CustomerWasCreated(
            $customer->id(),
            $customer->customer_id(),
            $customer->id_product(),
            $customer->inserted_money(),
            $customer->status(),
            $customer->remaining_machine_coins()
        ));

        return $customer;
    }

    public static function update(CustomerId $customer_id, ItemId $id_product,CustomerInsertedMoney $inserted_money,CustomerStatus $status,array $remaining_machine_coins): self
    {

        $customer = new self($customer_id, $id_product,$inserted_money,$status,$remaining_machine_coins);

        $customer->record(new CustomerWasUpdated(
            $customer->id(),
            $customer->customer_id(),
            $customer->id_product(),
            $customer->inserted_money(),
            $customer->status(),
            $customer->remaining_machine_coins()
        ));

        return $customer;
    }


    public static function checkout(CustomerId $customer_id, ItemId $id_product,CustomerInsertedMoney $inserted_money,CustomerStatus $status,array $remaining_machine_coins): self
    {

        $customer = new self($customer_id, $id_product,$inserted_money,$status,$remaining_machine_coins);

        $customer->record(new CustomerWasCheckout(
            $customer->id(),
            $customer->customer_id(),
            $customer->id_product(),
            $customer->inserted_money(),
            $customer->status(),
            $customer->remaining_machine_coins()
        ));

        return $customer;
    }


    public static function reset(CustomerId $customer_id, ItemId $id_product,CustomerInsertedMoney $inserted_money,CustomerStatus $status,array $remaining_machine_coins): self
    {

        $customer = new self($customer_id, $id_product,$inserted_money,$status,$remaining_machine_coins);

        $customer->record(new CustomerWasReset(
            $customer->id(),
            $customer->customer_id(),
            $customer->id_product(),
            $customer->inserted_money(),
            $customer->status(),
            $customer->remaining_machine_coins()
        ));

        return $customer;
    }

}
