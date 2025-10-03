<?php

namespace App\DataFixtures;

use App\Factory\PuzzleAnswerFactory;
use App\Factory\UserAnswerFactory;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class UserAnswerFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager): void
    {
        foreach (PuzzleAnswerFactory::all() as $puzzleAnswer) {
            UserAnswerFactory::createMany(rand(1, 5), [
                'puzzleAnswer' => $puzzleAnswer,
            ]);
        }
    }

    public function getDependencies(): array
    {
        return [
            PuzzleAnswerFixtures::class,
        ];
    }
}
