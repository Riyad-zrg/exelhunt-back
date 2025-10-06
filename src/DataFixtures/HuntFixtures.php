<?php

namespace App\DataFixtures;

use App\Factory\HuntFactory;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class HuntFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        // $product = new Product();
        // $manager->persist($product);
        HuntFactory::createMany(100);
        $manager->flush();
    }
}
