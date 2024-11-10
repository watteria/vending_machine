<?php

namespace App\Context\Items\Item\Infrastructure\Persistence\Doctrine;

use App\Context\Items\Item\Domain\Repository\ItemRepository;
use App\Context\Items\Item\Domain\Item;
use App\SharedKernel\Infrastructure\Persistence\Doctrine\DoctrineRepository;
use Doctrine\ORM\NativeQuery;
use Doctrine\ORM\Query\ResultSetMapping;

class MysqlDoctrineItemRepository extends DoctrineRepository implements ItemRepository
{
    protected function entity(): string
    {
        return Item::class;
    }

    public function search( $item_id): ?Item
    {

        $where = 'WHERE item_id="'.$item_id.'" ';
        $sql = <<<SQL
            SELECT item_id, product_name,quantity,price FROM items
            $where
        SQL;
        $query = $this->getNativeQuery($sql);

        $respuesta=$query->getArrayResult();
        if(isset($respuesta[0]['item_id'])){
            $item= new Item($respuesta[0]['item_id'],$respuesta[0]['product_name'],$respuesta[0]['quantity'],$respuesta[0]['price']);


            return $item;
        }else{
            return null;
        }


    }
    public function searchAll(): array
    {
        $where = 'WHERE 1';
        $sql = <<<SQL
            SELECT item_id, product_name,quantity,price FROM items
            $where
        SQL;

        $query = $this->getNativeQuery($sql);

        $respuesta=$query->getResult();
        return $respuesta;
    }




    public function save(Item $item): void
    {

        $sql = 'INSERT INTO items (item_id, product_name, quantity, price)
        VALUES (:item_id, :product_name, :quantity, :price)
        ON DUPLICATE KEY UPDATE
            product_name = VALUES(product_name),
            quantity = VALUES(quantity),
            price = VALUES(price)';

        $this->entityManager->getConnection()->executeStatement(
            $sql,
            [
                'item_id' => $item->item_id(),
                'product_name' => $item->product_name(),
                'quantity' => $item->quantity(),
                'price' => $item->price(),
            ]
        );
    }



    public function delete(Item $item): void
    {

        $sql = 'DELETE FROM  items WHERE item_id=:item_id';

        $this->entityManager->getConnection()->executeStatement(
            $sql,
            [
                'item_id' => $item->item_id()
            ]
        );
    }

    protected function getNativeQuery(string $sql): NativeQuery
    {
        $rsm = new ResultSetMapping();
        $rsm->addEntityResult(self::entity(), 'u');
        $rsm->addFieldResult('u', 'item_id', 'item_id');
        $rsm->addFieldResult('u', 'product_name', 'product_name');
        $rsm->addFieldResult('u', 'quantity', 'quantity');
        $rsm->addFieldResult('u', 'price', 'price');

        return $this->entityManager->createNativeQuery($sql, $rsm);
    }

}
