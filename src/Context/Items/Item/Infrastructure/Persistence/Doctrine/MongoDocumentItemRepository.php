<?php

namespace App\Context\Items\Item\Infrastructure\Persistence\Doctrine;

use App\Context\Items\Item\Domain\Item;
use App\Context\Items\Item\Domain\Repository\ItemRepository;
use App\SharedKernel\Infrastructure\Persistence\Doctrine\MongoRepository;

class MongoDocumentItemRepository extends MongoRepository  implements ItemRepository
{
    protected function entity(): string
    {
        return Item::class;
    }

    public function searchAll(): array
    {

        return $this->repository()->findAll();
    }

    public function search($item_id): ?Item
    {
        return $this->repository()->find($item_id);
    }

    public function save(Item $item): void
    {
        $this->doPersist($item);
    }

    public function delete(Item $item): void
    {
        $this->documentManager->remove($item);
        $this->documentManager->flush();
    }

}
