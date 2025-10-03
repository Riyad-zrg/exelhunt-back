<?php

namespace App\DataFixtures;

use App\Factory\HuntFactory;
use App\Factory\ParticipationFactory;
use App\Factory\UserFactory;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class ParticipationFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager): void
    {
        $hunts = HuntFactory::all();
        $users = UserFactory::all();

        foreach ($hunts as $hunt) {
            $nbPlayers = rand(3, 8);

            for ($i = 0; $i < $nbPlayers; ++$i) {
                $player = $users[array_rand($users)];

                $participation = ParticipationFactory::createOne([
                    'hunt' => $hunt,
                    'player' => $player,
                    'tracking' => 'puzzle_'.rand(1, 10),
                ]);
                if (rand(1, 100) <= 30) {
                    $participation->setGlobalTime(
                        new \DateTime(sprintf('-%d minutes', rand(30, 180)))
                    );
                }
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
