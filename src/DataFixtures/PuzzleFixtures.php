<?php

namespace App\DataFixtures;

use App\Entity\Hunt;
use App\Factory\PuzzleFactory;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class PuzzleFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $hunts = $manager->getRepository(Hunt::class)->findAll();

        foreach ($hunts as $hunt) {
            $nbPuzzles = random_int(3, 7);

            for ($i = 1; $i <= $nbPuzzles; ++$i) {
                PuzzleFactory::createOne([
                    'hunt' => $hunt,
                    'index' => $i,
                ]);
            }
        }

        $manager->flush();
    }
}
