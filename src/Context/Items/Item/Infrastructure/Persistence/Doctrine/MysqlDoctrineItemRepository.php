<?php

namespace App\Context\Items\Item\Infrastructure\Persistence\Doctrine;

use App\Context\Items\Item\Domain\Repository\ItemRepository;
use App\Context\Items\Item\Domain\Item;
use App\Context\Items\Item\Domain\ValueObject\ItemId;
use App\Context\Items\Item\Domain\ValueObject\ItemPrice;
use App\Context\Items\Item\Domain\ValueObject\ItemProductName;
use App\Context\Items\Item\Domain\ValueObject\ItemQuantity;
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
        foreach ($respuesta as $item) {
            return new Item($item['item_id'],$item['product_name'],$item['quantity'],$item['price']);
        }

            return null;



    }
    public function searchAll(): array
    {
        $where = 'WHERE 1';
        $sql = <<<SQL
            SELECT item_id, product_name,quantity,price FROM items
            $where
        SQL;

        $respuesta=array();
        $query = $this->getNativeQuery($sql);
        $query_response=$query->getArrayResult();
        foreach($query_response as $item){
            $respuesta[]= new Item($item['item_id'],$item['product_name'],$item['quantity'],$item['price']);
        }
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
                'item_id' => $item->item_id()->value(),
                'product_name' => $item->product_name()->value(),
                'quantity' => $item->quantity()->value(),
                'price' => $item->price()->value(),
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
