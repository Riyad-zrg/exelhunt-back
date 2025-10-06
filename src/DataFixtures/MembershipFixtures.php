<?php

namespace App\DataFixtures;

use App\Factory\MembershipFactory;
use App\Factory\TeamFactory;
use App\Factory\UserFactory;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class MembershipFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager): void
    {
        $teams = TeamFactory::all();

        foreach ($teams as $team) {
            $nbMembers = rand(1, 4);
            $rolesHierarchy = ['OWNER', 'MODERATOR', 'DEVELOPER', 'TESTER'];

            for ($i = 0; $i < $nbMembers; ++$i) {
                MembershipFactory::createOne([
                    'team' => $team,
                    'member' => UserFactory::random(),
                    'role' => [$rolesHierarchy[$i]],
                ]);
            }
        }
        $manager->flush();
    }

    public function getDependencies(): array
    {
        return [
            UserFixtures::class,
        ];
    }
}
