<?php

namespace App\DataFixtures;

use App\Factory\HuntFactory;
use App\Factory\TeamPlayerFactory;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Random\RandomException;

class TeamPlayerFixtures extends Fixture
{
    /**
     * @throws RandomException
     */
    public function load(ObjectManager $manager): void
    {
        $hunts = HuntFactory::createMany(50, function () {
            $isPlayable = 1 === random_int(0, 1);

            return [
                'isTeamPlayable' => $isPlayable,
                'teamPlayerMax' => $isPlayable ? random_int(2, 8) : null,
            ];
        });

        foreach ($hunts as $hunt) {
            if (!$hunt->isTeamPlayable()) {
                continue;
            }

            $max = $hunt->getTeamPlayerMax() ?? 5;
            $count = random_int(1, min(5, max(1, $max)));

            TeamPlayerFactory::createMany($count, function () use ($hunt) {
                $isPublic = (bool) random_int(0, 1);

                $overrides = [
                    'hunt' => $hunt,
                    'isPublic' => $isPublic,
                    'nbPlayers' => random_int(1, max(1, $hunt->getNbPlayers() ?? 4)),
                ];

                return $overrides;
            });
        }

        $manager->flush();
    }
}
