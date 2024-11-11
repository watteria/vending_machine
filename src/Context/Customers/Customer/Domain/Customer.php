<?php

namespace App\Context\Customers\Customer\Domain;

use App\Context\Customers\Customer\Domain\Event\CustomerWasCheckout;
use App\Context\Customers\Customer\Domain\Event\CustomerWasCreated;
use App\Context\Customers\Customer\Domain\Event\CustomerWasReset;
use App\Context\Customers\Customer\Domain\Event\CustomerWasUpdated;
use App\SharedKernel\Domain\Aggregate\AggregateRoot;

class Customer extends AggregateRoot
{
    public function __construct(private readonly string $customer_id, private readonly string $id_product, private readonly string $inserted_money, private readonly string $status, private readonly string $remaining_machine_coins)
    {
    }
    public function id(): string
    {
        return $this->customer_id;
    }
    public function customer_id(): string
    {
        return $this->customer_id;
    }

    public function id_product(): string
    {
        return $this->id_product;
    }

    public function inserted_money(): string
    {
        return $this->inserted_money;
    }
    public function remaining_machine_coins(): string
    {
        return $this->remaining_machine_coins;
    }

    public function status(): string
    {
        return $this->status;
    }


    public static function create(string $customer_id, string $id_product,string $inserted_money,string $status,string $remaining_machine_coins): self
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

    public static function update(string $customer_id, string $id_product,string $inserted_money,string $status,string $remaining_machine_coins): self
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


    public static function checkout(string $customer_id, string $id_product,string $inserted_money,string $status,string $remaining_machine_coins): self
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


    public static function reset(string $customer_id, string $id_product,string $inserted_money,string $status,string $remaining_machine_coins): self
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
