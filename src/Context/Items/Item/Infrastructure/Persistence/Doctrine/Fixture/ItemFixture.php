<?php

namespace App\Context\Items\Item\Infrastructure\Persistence\Doctrine\Fixture;

use App\Tests\Unit\Items\Item\Domain\ItemMother;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class ItemFixture extends Fixture
{
    public function load(ObjectManager $manager)
    {
        $manager->persist(ItemMother::default());
        $manager->flush();
    }
}
