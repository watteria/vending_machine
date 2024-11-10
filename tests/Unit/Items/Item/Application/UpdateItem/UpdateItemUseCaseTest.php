<?php

namespace App\Tests\Unit\Items\Item\Application\UpdateItem;

use App\Context\Items\Item\Application\UpdateItem\UpdateItemUseCase;
use App\Context\Items\Item\Domain\Event\ItemWasUpdated;
use App\Context\Items\Item\Domain\Item;
use App\Context\Items\Item\Domain\Repository\ItemRepository;
use App\SharedKernel\Domain\Bus\Event\EventBus;
use App\Tests\Unit\Items\Item\Domain\ItemMother;
use App\Tests\Unit\SharedKernel\UnitTestCase;

class UpdateItemUseCaseTest extends UnitTestCase
{
    public function test_it_updates_item(): void
    {
        $repository = $this->createMock(ItemRepository::class);
        $eventBus = $this->createMock(EventBus::class);
        $item = ItemMother::default();
        $nombre_test = 'producto_test' . rand();
        $updatedItem = new Item($item->item_id(), $nombre_test, $item->quantity(), $item->price());

        $repository
            ->expects(self::once())
            ->method('save')
            ->with($this->callback(function (Item $savedItem) use ($nombre_test) {
                return $savedItem->product_name() === $nombre_test;
            }));

        $eventBus
            ->expects(self::once())
            ->method('publish')
            ->with($this->callback(function ($event) use ($updatedItem) {
                return $event instanceof ItemWasUpdated &&
                    $event->aggregateId() === $updatedItem->id() &&
                    $event->item_id() === $updatedItem->item_id() &&
                    $event->product_name() === $updatedItem->product_name() &&
                    $event->quantity() === $updatedItem->quantity() &&
                    $event->price() === $updatedItem->price();
            }));

        $useCase = new UpdateItemUseCase($repository, $eventBus);
        $useCase->__invoke($updatedItem->item_id(), $updatedItem->product_name(), $updatedItem->quantity(), $updatedItem->price());

        $repository
            ->expects(self::once())
            ->method('search')
            ->with($item->item_id())
            ->willReturn($updatedItem);

        $foundItem = $repository->search($item->item_id());
        $this->assertEquals($nombre_test, $foundItem->product_name());
    }

}
