<?php

namespace App\DataFixtures;

use App\Factory\CodeFactory;
use App\Factory\HuntFactory;
use App\Factory\TeamPlayerFactory;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class CodeFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        // $product = new Product();
        // $manager->persist($product);
        TeamPlayerFactory::createMany(20);
        HuntFactory::createMany(20);
        $manager->flush();
    }
}
