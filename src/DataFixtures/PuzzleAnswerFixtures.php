<?php

namespace App\DataFixtures;

use App\Factory\PuzzleAnswerFactory;
use App\Factory\PuzzleFactory;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class PuzzleAnswerFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager): void
    {
        $puzzles = PuzzleFactory::all();

        foreach ($puzzles as $puzzle) {
            PuzzleAnswerFactory::createMany(rand(1, 3), [
                'puzzle' => $puzzle,
            ]);
        }
    }

    public function getDependencies(): array
    {
        return [
            PuzzleFixtures::class,
        ];
    }
}
