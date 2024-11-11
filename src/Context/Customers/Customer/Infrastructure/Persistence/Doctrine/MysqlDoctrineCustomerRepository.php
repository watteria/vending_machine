<?php

namespace App\Context\Customers\Customer\Infrastructure\Persistence\Doctrine;

use App\Context\Coins\Coin\Application\AllCoins\AllCoinsQuery;
use App\Context\Coins\Coin\Domain\Tools\MoneyChangeOnLimitedCoins;
use App\Context\Customers\Customer\Domain\Repository\CustomerRepository;
use App\Context\Customers\Customer\Domain\Customer;
use App\SharedKernel\Infrastructure\Persistence\Doctrine\DoctrineRepository;
use Doctrine\ORM\NativeQuery;
use Doctrine\ORM\Query\ResultSetMapping;

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
        if(isset($respuesta[0]['customer_id'])){
            $customer= new Customer($respuesta[0]['customer_id'],$respuesta[0]['id_product'],$respuesta[0]['inserted_money'],$respuesta[0]['status'],"");
            return $customer;
        }else{
            return null;
        }

    }
    public function searchAll(): array
    {
        $where = 'WHERE 1';
        $sql = <<<SQL
            SELECT customer_id, id_product,inserted_money,status FROM customers
            $where
        SQL;

        $query = $this->getNativeQuery($sql);

        $respuesta=$query->getResult();
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
                'customer_id' => $customer->customer_id(),
                'id_product' => $customer->id_product(),
                'inserted_money' => $customer->inserted_money(),
                'status' => $customer->status(),
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
