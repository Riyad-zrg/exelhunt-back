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
                    array_fill(0, 40, 'in_progress'),
                    array_fill(0, 14, 'paused'),
                    array_fill(0, 15, 'done'),
                    array_fill(0, 4, 'aborted'),
                    array_fill(0, 2, 'disqualified')
                );
                $tracking = $pool[array_rand($pool)];

                $data = [
                    'hunt' => $hunt,
                    'player' => $player,
                    'tracking' => $tracking,
                ];

                if (class_exists(TeamPlayerFactory::class) && $hunt->isTeamPlayable() && rand(1, 100) <= 35) {
                    $data['teamPlayer'] = TeamPlayerFactory::randomOrCreate(['hunt' => $hunt])->_real();
                }

                if ('pending' !== $tracking) {
                    $minutes = rand(5, 180);
                    $data['globalTime'] = (new \DateTime())->setTime(0, $minutes, rand(0, 59));
                } else {
                    $data['globalTime'] = null;
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
