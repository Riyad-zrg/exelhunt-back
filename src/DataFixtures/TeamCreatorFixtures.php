<?php

namespace App\DataFixtures;

use App\Factory\TeamCreatorFactory;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class TeamCreatorFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        TeamCreatorFactory::createMany(20);
        $manager->flush();
    }
}
