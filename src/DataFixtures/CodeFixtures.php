<?php

namespace App\DataFixtures;

use App\Factory\CodeFactory;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class CodeFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        // $product = new Product();
        // $manager->persist($product);
        CodeFactory::createMany(100);

        $manager->flush();
    }
}
