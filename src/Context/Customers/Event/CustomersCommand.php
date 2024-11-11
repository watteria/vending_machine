<?php

namespace App\Context\Customers\Event;

use App\SharedKernel\Domain\Bus\Command\Command;

abstract class CustomersCommand implements Command
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


    protected function boundedContext(): string
    {
        return 'customers';
    }
}
