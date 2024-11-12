<?php

namespace App\Context\Customers\Event;
use App\Context\Customers\Customer\Domain\ValueObject\CustomerId;
use App\Context\Customers\Customer\Domain\ValueObject\CustomerInsertedMoney;
use App\Context\Customers\Customer\Domain\ValueObject\CustomerStatus;
use App\Context\Items\Item\Domain\ValueObject\ItemId;
use App\SharedKernel\Domain\Bus\Event\DomainEvent;

abstract class CustomersDomainEvent extends DomainEvent
{

    public function __construct(
        string $id,
        private readonly CustomerId  $customer_id,
        private readonly ItemId $id_product,
        private readonly CustomerInsertedMoney  $inserted_money,
        private readonly CustomerStatus  $status,
        private readonly array  $remaining_machine_coins,
        string $eventId = null,
        string $occurredOn = null
    ) {
        parent::__construct($id, $eventId, $occurredOn);
    }


    public function toPrimitives(): array
    {
        return [
            'customer_id' => $this->customer_id,
            'id_product' => $this->id_product,
            'inserted_money' => $this->inserted_money,
            'status' => $this->status,
            'remaining_machine_coins' => $this->remaining_machine_coins,
        ];
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
