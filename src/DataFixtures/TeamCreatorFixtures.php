<?php

namespace App\DataFixtures;

use App\Factory\HuntFactory;
use App\Factory\TeamCreatorFactory;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Random\RandomException;

class TeamCreatorFixtures extends Fixture
{
    /**
     * @throws RandomException
     */
    public function load(ObjectManager $manager): void
    {
        // $product = new Product();
        // $manager->persist($product);
        $creators = TeamCreatorFactory::createMany(100);

        foreach ($creators as $creator) {
            HuntFactory::createMany(random_int(1, 5), function () use ($creator) {
                return [
                    'createdBy' => $creator,
                ];
            });
        }

        $manager->flush();
    }
}
