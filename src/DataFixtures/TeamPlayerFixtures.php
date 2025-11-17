<?php

namespace App\DataFixtures;

use App\Factory\HuntFactory;
use App\Factory\TeamPlayerFactory;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use Random\RandomException;

class TeamPlayerFixtures extends Fixture implements DependentFixtureInterface
{
    /**
     * @throws RandomException
     */
    public function load(ObjectManager $manager): void
    {
        $hunts = array_map(fn ($proxy) => $proxy->_real(), HuntFactory::all());

        foreach ($hunts as $hunt) {
            if (!$hunt->isTeamPlayable()) {
                continue;
            }

            $max = $hunt->getTeamPlayerMax() ?? 5;
            $count = random_int(1, min(5, max(1, $max)));

            TeamPlayerFactory::createMany($count, function () use ($hunt) {
                $isPublic = (bool) random_int(0, 1);

                return [
                    'hunt' => $hunt,
                    'isPublic' => $isPublic,
                    'nbPlayers' => random_int(1, max(1, $hunt->getNbPlayers() ?? 4)),
                ];
            });
        }

        $manager->flush();
    }

    public function getDependencies(): array
    {
        return [
            HuntFixtures::class,
        ];
    }
}
