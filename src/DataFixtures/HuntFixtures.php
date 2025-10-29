<?php

namespace App\DataFixtures;

use App\Entity\TeamCreator;
use App\Factory\HuntFactory;
use App\Factory\PuzzleFactory;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Random\RandomException;

class HuntFixtures extends Fixture
{
    /**
     * @throws RandomException
     */
    public function load(ObjectManager $manager): void
    {
        $visibilities = ['PUBLIC', 'PRIVATE', 'DEVELOPMENT', 'CLOSED'];

        $creators = $manager->getRepository(TeamCreator::class)->findAll();

        foreach ($visibilities as $visibility) {
            $creator = $creators ? $creators[array_rand($creators)] : null;

            $hunt = HuntFactory::createOne([
                'visibility' => $visibility,
                'createdBy' => $creator,
            ])->object();

            $nbPuzzles = random_int(3, 5);
            PuzzleFactory::createMany($nbPuzzles, function () use ($hunt) {
                return [
                    'hunt' => $hunt,
                ];
            });
        }

        $manager->flush();
    }
}
