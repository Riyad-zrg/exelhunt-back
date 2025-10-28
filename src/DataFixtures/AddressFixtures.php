<?php

namespace App\DataFixtures;

use App\Factory\HuntFactory;
use App\Factory\UserFactory;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class AddressFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        HuntFactory::createMany(3);
        UserFactory::createMany(50);
        $manager->flush();
    }
}
