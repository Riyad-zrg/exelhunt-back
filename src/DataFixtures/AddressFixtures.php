<?php

namespace App\DataFixtures;

use App\Factory\HuntFactory;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class AddressFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        HuntFactory::createMany(3);
        $manager->flush();
    }
}
