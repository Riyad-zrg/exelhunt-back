<?php

namespace App\DataFixtures;

use App\Factory\AddressFactory;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class AddressFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        AddressFactory::createMany(50);
        $manager->flush();
    }
}
