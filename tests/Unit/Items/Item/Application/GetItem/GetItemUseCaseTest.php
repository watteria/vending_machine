<?php

namespace App\Tests\Unit\Items\Item\Application\GetItem;

use App\Context\Items\Item\Application\GetItem\GetItemUseCase;
use App\Context\Items\Item\Domain\Repository\ItemRepository;
use App\Tests\Unit\Items\Item\Domain\ItemMother;
use App\Tests\Unit\SharedKernel\UnitTestCase;

class GetItemUseCaseTest extends UnitTestCase
{
    public function test_it_returns_item_when_found(): void
    {
        $repository = $this->createMock(ItemRepository::class);
        $item = ItemMother::default();
        $itemId = $item->item_id();

        $repository
            ->expects(self::once())
            ->method('search')
            ->with($itemId)
            ->willReturn($item);

        $useCase = new GetItemUseCase($repository);
        $result = $useCase->__invoke($itemId);

        $this->assertEquals($item, $result, 'The retrieved item should match the expected item.');
    }

    public function test_it_returns_null_when_item_not_found(): void
    {
        $repository = $this->createMock(ItemRepository::class);
        $itemId = 'non-existent-id';

        $repository
            ->expects(self::once())
            ->method('search')
            ->with($itemId)
            ->willReturn(null);

        $useCase = new GetItemUseCase($repository);
        $result = $useCase->__invoke($itemId);

        $this->assertNull($result, 'The result should be null when the item is not found.');
    }
}
