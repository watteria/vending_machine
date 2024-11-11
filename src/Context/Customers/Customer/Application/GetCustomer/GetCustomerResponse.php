<?php

namespace App\Context\Customers\Customer\Application\GetCustomer;

use App\Context\Customers\Customer\Domain\Customer;
use App\SharedKernel\Domain\Bus\Query\Response;

class GetCustomerResponse implements Response
{
    public function __construct(private readonly Customer $customer)
    {
    }

    public function result(): array
    {
        $result_final=[
            'customer_id' => $this->customer->customer_id(),
            'id_product' => $this->customer->id_product(),
            'inserted_money' => $this->customer->inserted_money(),
            'status' => $this->customer->status(),
            'remaining_machine_coins'=>$this->customer->remaining_machine_coins(),
        ];
        return $result_final;
    }

}
