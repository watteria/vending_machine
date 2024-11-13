<?php

namespace App\Context\Customers\Customer\Infrastructure\Persistence\Doctrine;

use App\Context\Customers\Customer\Domain\Customer;
use App\Context\Customers\Customer\Domain\Repository\CustomerRepository;
use App\SharedKernel\Infrastructure\Persistence\Doctrine\MongoRepository;

class MongoDocumentCustomerRepository extends MongoRepository  implements CustomerRepository
{
    protected function entity(): string
    {
        return Customer::class;
    }

    public function search($customer_id): ?Customer
    {
        return $this->documentManager->getRepository(Customer::class)->findOneBy(['customer_id' => $customer_id]);
    }

    public function searchAll(): array
    {
        return $this->documentManager->getRepository(Customer::class)->findAll();
    }

    public function save(Customer $customer): void
    {
        $this->documentManager->persist($customer);
        $this->documentManager->flush();
    }

    public function delete(Customer $customer): void
    {
        $this->documentManager->remove($customer);
        $this->documentManager->flush();
    }

}
