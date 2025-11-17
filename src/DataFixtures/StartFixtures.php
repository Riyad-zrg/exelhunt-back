<?php

namespace App\DataFixtures;

use App\Entity\Start;
use App\Factory\PuzzleFactory;
use App\Factory\StartFactory;
use App\Factory\UserFactory;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class StartFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager): void
    {
        $users = array_map(fn ($proxy) => $proxy->_real(), UserFactory::all());
        $puzzles = array_map(fn ($proxy) => $proxy->_real(), PuzzleFactory::all());

        if (empty($users) || empty($puzzles)) {
            return;
        }

        $playerUsers = array_filter($users, fn ($u) => in_array('ROLE_PLAYER', $u->getRoles(), true));

        if (empty($playerUsers)) {
            return;
        }

        $puzzlesByHunt = [];
        foreach ($puzzles as $puzzle) {
            $hunt = $puzzle->getHunt();
            if ($hunt) {
                $huntId = $hunt->getId();
                if (!isset($puzzlesByHunt[$huntId])) {
                    $puzzlesByHunt[$huntId] = [];
                }
                $puzzlesByHunt[$huntId][] = $puzzle;
            }
        }

        foreach ($puzzlesByHunt as $huntId => &$huntPuzzles) {
            usort($huntPuzzles, fn ($a, $b) => $a->getIndex() <=> $b->getIndex());
        }

        foreach ($playerUsers as $user) {
            if (empty($puzzlesByHunt)) {
                continue;
            }

            $randomHuntId = array_rand($puzzlesByHunt);
            $orderedPuzzles = $puzzlesByHunt[$randomHuntId];

            $count = rand(1, min(5, count($orderedPuzzles)));

            for ($i = 0; $i < $count; ++$i) {
                $puzzle = $orderedPuzzles[$i];

                $exists = $manager->getRepository(Start::class)->findOneBy([
                    'player' => $user,
                    'puzzle' => $puzzle,
                ]);

                if ($exists) {
                    continue;
                }

                StartFactory::createOne([
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
}
