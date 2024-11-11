<?php

namespace App\Context\Customers\Event;

use App\SharedKernel\Domain\Bus\Event\DomainEvent;

abstract class CustomersDomainEvent extends DomainEvent
{

    public function __construct(
        string $id,
        private readonly string $customer_id,
        private readonly string $id_product,
        private readonly string $inserted_money,
        private readonly string $status,
        private readonly string $remaining_machine_coins,
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

}
