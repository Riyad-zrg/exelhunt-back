<?php

namespace App\DataFixtures;

use App\Factory\HuntFactory;
use App\Factory\TeamPlayerFactory;
use App\Factory\CodeFactory;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Random\RandomException;

class CodeFixtures extends Fixture
{
    /**
     * @throws RandomException
     */
    public function load(ObjectManager $manager): void
    {
        $hunts = HuntFactory::createMany(20, function () {
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
                return [
                    'hunt' => $hunt,
                    'isPublic' => (bool) random_int(0, 1),
                    'nbPlayers' => random_int(1, max(1, $hunt->getNbPlayers() ?? 4)),
                ];
            });

            // Dans certains cas, créer un code directement lié à la Hunt (ex: code d'invitation global)
            if (random_int(1, 100) <= 30) {
                CodeFactory::createOne([
                    'hunt' => $hunt,
                    // expire dans 1 à 60 jours
                    'expireAt' => (new \DateTime())->modify('+' . random_int(1, 60) . ' days'),
                ]);
            }
        }

        $manager->flush();
    }
}
