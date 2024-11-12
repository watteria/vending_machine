<?php

namespace App\Context\Customers\Customer\Infrastructure\Persistence\Doctrine;

use App\Context\Customers\Customer\Domain\Repository\CustomerRepository;
use App\Context\Customers\Customer\Domain\Customer;
use App\SharedKernel\Infrastructure\Persistence\Doctrine\DoctrineRepository;
use Doctrine\ORM\NativeQuery;
use Doctrine\ORM\Query\ResultSetMapping;
use App\SharedKernel\Domain\Utils;

class MysqlDoctrineCustomerRepository extends DoctrineRepository implements CustomerRepository
{
    protected function entity(): string
    {
        return Customer::class;
    }

    public function search( $customer_id): ?Customer
    {
        $where = 'WHERE customer_id="'.$customer_id.'" ';
        $sql = <<<SQL
            SELECT customer_id, id_product,inserted_money,status FROM customers
            $where
        SQL;

        $query = $this->getNativeQuery($sql);

        $respuesta=$query->getArrayResult();
        foreach ($respuesta as $customer) {

            return new Customer($customer['customer_id'],$customer['id_product'],$customer['inserted_money'],$customer['status'],array());
        }
        return null;

    }
    public function searchAll(): array
    {
        $where = 'WHERE 1';
        $sql = <<<SQL
            SELECT customer_id, id_product,inserted_money,status FROM customers
            $where
        SQL;

        $query = $this->getNativeQuery($sql);

        $respuesta=array();
        $query = $this->getNativeQuery($sql);
        $query_response=$query->getArrayResult();
        foreach($query_response as $customer){
            $respuesta[]= new Customer($customer['customer_id'],$customer['id_product'],$customer['inserted_money'],$customer['status'],array());
        }
        return $respuesta;
    }




    public function save(Customer $customer): void
    {

        $sql = 'INSERT INTO customers (customer_id, id_product, inserted_money, status)
        VALUES (:customer_id, :id_product, :inserted_money, :status)
        ON DUPLICATE KEY UPDATE
            id_product = VALUES(id_product),
            inserted_money = VALUES(inserted_money),
            status = VALUES(status)';

        $this->entityManager->getConnection()->executeStatement(
            $sql,
            [
                'customer_id' => $customer->customer_id()->value(),
                'id_product' => $customer->id_product()->value(),
                'inserted_money' => $customer->inserted_money()->value(),
                'status' => $customer->status()->value(),
            ]
        );
    }



    public function delete(Customer $customer): void
    {

        $sql = 'DELETE FROM  customers WHERE customer_id=:customer_id';

        $this->entityManager->getConnection()->executeStatement(
            $sql,
            [
                'customer_id' => $customer->customer_id()
            ]
        );
    }

    protected function getNativeQuery(string $sql): NativeQuery
    {
        $rsm = new ResultSetMapping();
        $rsm->addEntityResult(self::entity(), 'u');
        $rsm->addFieldResult('u', 'customer_id', 'customer_id');
        $rsm->addFieldResult('u', 'id_product', 'id_product');
        $rsm->addFieldResult('u', 'inserted_money', 'inserted_money');
        $rsm->addFieldResult('u', 'status', 'status');

        return $this->entityManager->createNativeQuery($sql, $rsm);
    }

}
