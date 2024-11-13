<?php

namespace App\Context\Coins\Coin\Infrastructure\Persistence\Doctrine;

use App\Context\Coins\Coin\Domain\Repository\CoinRepository;
use App\Context\Coins\Coin\Domain\Coin;
use App\SharedKernel\Infrastructure\Persistence\Doctrine\DoctrineRepository;
use Doctrine\ORM\NativeQuery;
use Doctrine\ORM\Query\ResultSetMapping;

class MysqlDoctrineCoinRepository extends DoctrineRepository implements CoinRepository
{
    protected function entity(): string
    {
        return Coin::class;
    }

    public function search( $coin_id): ?Coin
    {
        $where = 'WHERE coin_id="'.$coin_id.'" ';
        $sql = <<<SQL
            SELECT * FROM coins
            $where
        SQL;
        $query = $this->getNativeQuery($sql);

        $respuesta=$query->getArrayResult();
        foreach ($respuesta as $coin) {
            return new Coin($coin['coin_id'],$coin['quantity'],$coin['coin_value'],$coin['valid_for_change']);
        }
        return null;

    }
    public function searchAll(): array
    {
        $where = 'WHERE 1';
        $sql = <<<SQL
            SELECT coin_id, quantity,coin_value,valid_for_change FROM coins
            $where
        SQL;

        $respuesta=array();
        $query = $this->getNativeQuery($sql);

        $query_response=$query->getArrayResult();
        foreach($query_response as $coin){
            $respuesta[]= new Coin($coin['coin_id'],$coin['quantity'],$coin['coin_value'],$coin['valid_for_change']);
        }
        return $respuesta;
    }




    public function save(Coin $coin): void
    {

        $sql = 'INSERT INTO coins (coin_id,  quantity, coin_value,valid_for_change)
        VALUES (:coin_id,  :quantity, :coin_value, :valid_for_change)
        ON DUPLICATE KEY UPDATE 
            quantity = VALUES(quantity),
            coin_value = VALUES(coin_value),
             valid_for_change= VALUES(valid_for_change)';

        $this->entityManager->getConnection()->executeStatement(
            $sql,
            [
                'coin_id' => $coin->coin_id()->value(),
                'quantity' => $coin->quantity()->value(),
                'coin_value' => $coin->coin_value()->value(),
                'valid_for_change' => (int)$coin->valid_for_change()->value()
            ]
        );
    }



    public function delete(Coin $coin): void
    {

        $sql = 'DELETE FROM coins WHERE coin_id=:coin_id';

        $this->entityManager->getConnection()->executeStatement(
            $sql,
            [
                'coin_id' => $coin->coin_id()->value()
            ]
        );
    }

    protected function getNativeQuery(string $sql): NativeQuery
    {
        $rsm = new ResultSetMapping();
        $rsm->addEntityResult(self::entity(), 'u');
        $rsm->addFieldResult('u', 'coin_id', 'coin_id');
        $rsm->addFieldResult('u', 'quantity', 'quantity');
        $rsm->addFieldResult('u', 'coin_value', 'coin_value');
        $rsm->addFieldResult('u', 'valid_for_change', 'valid_for_change');

        return $this->entityManager->createNativeQuery($sql, $rsm);
    }

}
