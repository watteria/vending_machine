<?php

namespace App\Tests\Unit\Items\Item\Application\CreateItem;

use App\Context\Items\Item\Application\CreateItem\CreateItemUseCase;
use App\Context\Items\Item\Domain\Event\ItemWasCreated;
use App\Context\Items\Item\Domain\Item;
use App\Context\Items\Item\Domain\Repository\ItemRepository;
use App\SharedKernel\Domain\Bus\Event\EventBus;
use App\Tests\Unit\Items\Item\Domain\ItemMother;
use App\Tests\Unit\SharedKernel\UnitTestCase;

class CreateItemUseCaseTest extends UnitTestCase
{
    public function test_it_creates_new_item(): void
    {
        $repository = $this->createMock(ItemRepository::class);
        $eventBus = $this->createMock(EventBus::class);

        $expectedItem = ItemMother::default();

        $repository
            ->expects(self::once())
            ->method('save')
            ->with($this->callback(function (Item $savedItem) use ($expectedItem) {
                return $savedItem->item_id() === $expectedItem->item_id() &&
                    $savedItem->product_name() === $expectedItem->product_name() &&
                    $savedItem->quantity() === $expectedItem->quantity() &&
                    $savedItem->price() === $expectedItem->price();
            }));

        $eventBus
            ->expects(self::once())
            ->method('publish')
            ->with($this->callback(function ($event) use ($expectedItem) {
                return $event instanceof ItemWasCreated &&
                    $event->aggregateId() === $expectedItem->id() &&
                    $event->item_id() === $expectedItem->item_id() &&
                    $event->product_name() === $expectedItem->product_name() &&
                    $event->quantity() === $expectedItem->quantity() &&
                    $event->price() === $expectedItem->price();
            }));

        $useCase = new CreateItemUseCase($repository, $eventBus);
        $useCase->__invoke($expectedItem->item_id(), $expectedItem->product_name(), $expectedItem->quantity(), $expectedItem->price());
    }
}
