<?php

namespace App\DataFixtures;

use App\Factory\HuntFactory;
use App\Factory\ParticipationFactory;
use App\Factory\TeamPlayerFactory;
use App\Factory\UserFactory;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class ParticipationFixtures extends Fixture implements DependentFixtureInterface
{
    /**
     * @throws \Exception
     */
    public function load(ObjectManager $manager): void
    {
        $hunts = array_map(fn ($proxy) => $proxy->_real(), HuntFactory::all());
        $users = array_map(fn ($proxy) => $proxy->_real(), UserFactory::all());

        foreach ($hunts as $hunt) {
            $shuffledUsers = $users;
            shuffle($shuffledUsers);
            $nbPlayers = rand(3, min(8, count($shuffledUsers)));

            for ($i = 0; $i < $nbPlayers; ++$i) {
                $player = $shuffledUsers[$i];

                $pool = array_merge(
                    array_fill(0, 25, 'pending'),
                    array_fill(0, 35, 'in_progress'),
                    array_fill(0, 10, 'paused'),
                    array_fill(0, 15, 'done'),
                    array_fill(0, 4, 'aborted'),
                    array_fill(0, 4, 'abandoned'),
                    array_fill(0, 3, 'timeout'),
                    array_fill(0, 2, 'blocked'),
                    array_fill(0, 2, 'disqualified')
                );
                $tracking = $pool[array_rand($pool)];

                $data = [
                    'hunt' => $hunt,
                    'player' => $player,
                    'tracking' => $tracking,
                ];

                if (class_exists(TeamPlayerFactory::class) && rand(1, 100) <= 35) {
                    $data['teamPlayer'] = TeamPlayerFactory::randomOrCreate(['hunt' => $hunt])->_real();
                }

                $puzzles = $hunt->getPuzzles()->toArray();
                if (!empty($puzzles) && rand(1, 100) <= 60) {
                    $data['globalTime'] = new \DateTime(sprintf('-%d minutes', rand(5, 180)));
                }

                ParticipationFactory::createOne($data);
            }
        }

        $manager->flush();
    }

    public function getDependencies(): array
    {
        return [
            HuntFixtures::class,
            UserFixtures::class,
        ];
    }
}
