<?php

namespace App\DataFixtures;

use App\Factory\HasStartedFactory;
use App\Factory\PuzzleFactory;
use App\Factory\UserFactory;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class HasStartedFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager): void
    {
        $users = UserFactory::all();
        $puzzles = PuzzleFactory::all();

        if (empty($users) || empty($puzzles)) {
            return;
        }

        foreach ($users as $user) {
            $startedPuzzles = $this->getRandomElements($puzzles, rand(1, 5));

            foreach ($startedPuzzles as $puzzle) {
                HasStartedFactory::createOne([
                    'player' => $user,
                    'puzzle' => $puzzle,
                ]);
            }
        }

        $manager->flush();
    }

    public function getDependencies(): array
    {
        return [
            UserFixtures::class,
            PuzzleFixtures::class,
        ];
    }

    private function getRandomElements(array $array, int $count): array
    {
        if ($count >= count($array)) {
            return $array;
        }

        $keys = array_rand($array, $count);
        if (!is_array($keys)) {
            $keys = [$keys];
        }

        return array_map(fn ($key) => $array[$key], $keys);
    }
}
