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
            ->expects(self::exactly(2))
            ->method('search')
            ->withConsecutive([$item->item_id()->value()], [$item->item_id()->value()])
            ->willReturnOnConsecutiveCalls($item, null);

        $repository
            ->expects(self::once())
            ->method('delete')
            ->with($this->callback(function (Item $deletedItem) use ($item) {
                return $deletedItem->item_id()->value() === $item->item_id()->value();
            }));


        $useCase = new DeleteItemUseCase($repository, $eventBus);
        $useCase->__invoke($item->item_id(),$item->product_name(),$item->quantity(),$item->price());


        $deletedItem = $repository->search($item->item_id()->value());
        $this->assertNull($deletedItem);
    }
}
