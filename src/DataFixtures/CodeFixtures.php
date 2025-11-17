<?php

namespace App\DataFixtures;

use App\Entity\TeamPlayer;
use App\Factory\CodeFactory;
use App\Factory\HuntFactory;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use Random\RandomException;

class CodeFixtures extends Fixture implements DependentFixtureInterface
{
    /**
     * @throws RandomException
     */
    public function load(ObjectManager $manager): void
    {
        $teamPlayerRepository = $manager->getRepository(TeamPlayer::class);
        $allTeamPlayers = $teamPlayerRepository->findAll();

        foreach ($allTeamPlayers as $teamPlayer) {
            if (random_int(1, 100) <= 70) {
                CodeFactory::createOne([
                    'teamPlayer' => $teamPlayer,
                    'hunt' => null,
                    'expireAt' => (new \DateTime())->modify('+'.random_int(1, 60).' days'),
                ]);
            }
        }

        $allHunts = array_map(fn ($proxy) => $proxy->_real(), HuntFactory::all());
        foreach ($allHunts as $hunt) {
            if (random_int(1, 100) <= 50) {
                CodeFactory::createOne([
                    'hunt' => $hunt,
                    'teamPlayer' => null,
                    'expireAt' => (new \DateTime())->modify('+'.random_int(1, 60).' days'),
                ]);
            }
        }

        $manager->flush();
    }

    public function getDependencies(): array
    {
        return [
            HuntFixtures::class,
            TeamPlayerFixtures::class,
        ];
    }
}
