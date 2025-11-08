<?php

namespace App\DataFixtures;

use App\Entity\HasStarted;
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

        $playerUsers = array_filter($users, function ($u) {
            return is_object($u) && method_exists($u, 'getRoles') && in_array('PLAYER', $u->getRoles(), true);
        });

        if (empty($playerUsers)) {
            return;
        }

        foreach ($playerUsers as $user) {
            $count = rand(1, min(5, count($puzzles)));
            $startedPuzzles = $this->getRandomElements($puzzles, $count);

            foreach ($startedPuzzles as $puzzle) {
                $exists = $manager->getRepository(HasStarted::class)->findOneBy([
                    'player' => $user,
                    'puzzle' => $puzzle,
                ]);

                if ($exists) {
                    // déjà présent, on saute
                    continue;
                }

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
