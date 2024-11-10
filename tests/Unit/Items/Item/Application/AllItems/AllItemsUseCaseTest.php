<?php

namespace App\Tests\Unit\Items\Item\Application\AllItems;

use App\Context\Items\Item\Application\AllItems\AllItemsUseCase;
use App\Context\Items\Item\Domain\Repository\ItemRepository;
use App\Tests\Unit\Items\Item\Domain\ItemMother;
use App\Tests\Unit\SharedKernel\UnitTestCase;

class AllItemsUseCaseTest extends UnitTestCase
{
    public function test_it_returns_all_items(): void
    {
        $repository = $this->createMock(ItemRepository::class);
        $item1 = ItemMother::default();
        $item2 = ItemMother::default();

        $items = [$item1, $item2];

        $repository
            ->expects(self::once())
            ->method('searchAll')
            ->willReturn($items);

        $useCase = new AllItemsUseCase($repository);
        $result = $useCase->__invoke();

        $this->assertEquals($items, $result);
    }

    public function test_it_returns_null_when_no_items_found(): void
    {
        $repository = $this->createMock(ItemRepository::class);

        $repository
            ->expects(self::once())
            ->method('searchAll')
            ->willReturn([]);


        $useCase = new AllItemsUseCase($repository);
        $result = $useCase->__invoke();


        $this->assertNull($result);
    }
}
