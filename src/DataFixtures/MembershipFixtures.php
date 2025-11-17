<?php

namespace App\DataFixtures;

use App\Entity\Team;
use App\Entity\TeamCreator;
use App\Entity\TeamPlayer;
use App\Factory\MembershipFactory;
use App\Factory\UserFactory;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class MembershipFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager): void
    {
        $teams = $manager->getRepository(Team::class)->findAll();

        foreach ($teams as $team) {
            $nbMembers = rand(1, 4);

            $assignedUserIds = [];

            $ownerUser = UserFactory::random()->_real();
            $assignedUserIds[$ownerUser->getId()] = true;

            MembershipFactory::createOne([
                'team' => $team,
                'member' => $ownerUser,
                'role' => ['OWNER'],
            ]);

            $remaining = max(0, $nbMembers - 1);

            for ($i = 0; $i < $remaining; ++$i) {
                $user = UserFactory::random()->_real();
                $uid = $user->getId();

                if (isset($assignedUserIds[$uid])) {
                    $user = UserFactory::createOne()->_real();
                }

                $assignedUserIds[$user->getId()] = true;

                if ($team instanceof TeamPlayer) {
                    $role = ['MEMBER'];
                } elseif ($team instanceof TeamCreator) {
                    $roles = ['MODERATOR', 'DEVELOPER', 'TESTER'];
                    $role = [$roles[array_rand($roles)]];
                } else {
                    $role = ['MEMBER'];
                }

                MembershipFactory::createOne([
                    'team' => $team,
                    'member' => $user,
                    'role' => $role,
                ]);
            }
        }

        $manager->flush();
    }

    public function getDependencies(): array
    {
        return [
            UserFixtures::class,
            TeamCreatorFixtures::class,
            TeamPlayerFixtures::class,
        ];
    }
}
