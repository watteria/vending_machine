<?php

namespace App\Context\Customers\Customer\Infrastructure\Persistence\Doctrine\Fixture;

use App\Tests\Unit\Customers\Customer\Domain\CustomerMother;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class CustomerFixture extends Fixture
{
    public function load(ObjectManager $manager)
    {
        $manager->persist(CustomerMother::default());
        $manager->flush();
    }
}
