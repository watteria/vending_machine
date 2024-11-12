<?php

namespace App\Tests\Unit\Items\Item\Application\UpdateItem;

use App\Context\Items\Item\Application\UpdateItem\UpdateItemUseCase;
use App\Context\Items\Item\Domain\Event\ItemWasUpdated;
use App\Context\Items\Item\Domain\Item;
use App\Context\Items\Item\Domain\Repository\ItemRepository;
use App\Context\Items\Item\Domain\ValueObject\ItemProductName;
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
        $nombre_test =  new ItemProductName('producto_test' . rand());
        $updatedItem = new Item($item->item_id(), $nombre_test, $item->quantity(), $item->price());

        $repository
            ->expects(self::once())
            ->method('save')
            ->with($this->callback(function (Item $savedItem) use ($nombre_test) {
                return $savedItem->product_name()->value() === $nombre_test->value();
            }));

        $eventBus
            ->expects(self::once())
            ->method('publish')
            ->with($this->callback(function ($event) use ($updatedItem) {
                return $event instanceof ItemWasUpdated &&
                    $event->aggregateId() === $updatedItem->id()->value() &&
                    $event->item_id()->value() === $updatedItem->item_id()->value() &&
                    $event->product_name()->value() === $updatedItem->product_name()->value() &&
                    $event->quantity()->value() === $updatedItem->quantity()->value() &&
                    $event->price()->value() === $updatedItem->price()->value();
            }));

        $useCase = new UpdateItemUseCase($repository, $eventBus);
        $useCase->__invoke($updatedItem->item_id(), $updatedItem->product_name(), $updatedItem->quantity(), $updatedItem->price());

        $repository
            ->expects(self::once())
            ->method('search')
            ->with($item->item_id()->value())
            ->willReturn($updatedItem);

        $foundItem = $repository->search($item->item_id()->value());
        $this->assertEquals($nombre_test->value(), $foundItem->product_name()->value());
    }

}
