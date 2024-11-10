<?php

namespace App\Tests\Unit\Items\Item\Application\DeleteItem;

use App\Context\Items\Item\Application\DeleteItem\DeleteItemUseCase;
use App\Context\Items\Item\Domain\Item;
use App\Context\Items\Item\Domain\Repository\ItemRepository;
use App\SharedKernel\Domain\Bus\Event\EventBus;
use App\Tests\Unit\Items\Item\Domain\ItemMother;
use App\Tests\Unit\SharedKernel\UnitTestCase;

class DeleteItemUseCaseTest extends UnitTestCase
{
    public function test_it_deletes_item_and_confirms_it_is_gone(): void
    {
        $repository = $this->createMock(ItemRepository::class);
        $eventBus = $this->createMock(EventBus::class);
        $item = ItemMother::default();



        $repository
            ->expects(self::once())
            ->method('delete')
            ->with($this->callback(function (Item $deletedItem) use ($item) {
                return $deletedItem->item_id() === $item->item_id();
            }));


        $useCase = new DeleteItemUseCase($repository, $eventBus);
        $useCase->__invoke($item->item_id(),$item->product_name(),$item->quantity(),$item->price());


        $repository
            ->expects(self::once())
            ->method('search')
            ->with($item->item_id())
            ->willReturn(null);


        $deletedItem = $repository->search($item->item_id());
        $this->assertNull($deletedItem);
    }
}