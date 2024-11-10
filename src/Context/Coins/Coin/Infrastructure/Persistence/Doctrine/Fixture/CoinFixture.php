<?php

namespace App\Context\Coins\Coin\Infrastructure\Persistence\Doctrine\Fixture;

use App\Tests\Unit\Coins\Coin\Domain\CoinMother;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class CoinFixture extends Fixture
{
    public function load(ObjectManager $manager)
    {
        $manager->persist(CoinMother::default());
        $manager->flush();
    }
}
