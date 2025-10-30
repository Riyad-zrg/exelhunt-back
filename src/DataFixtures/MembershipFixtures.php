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
            // Nombre total de membres à créer pour cette équipe
            $nbMembers = rand(1, 4);

            // Liste des users déjà assignés à cette team pour éviter les doublons
            $assignedUserIds = [];

            // Assurer la présence d'un OWNER si possible
            // Pour TeamPlayer: roles = OWNER | MEMBER
            // Pour TeamCreator: roles = OWNER | MODERATOR | DEVELOPER | TESTER

            // Créer d'abord un OWNER (si on veut garantir un owner par équipe)
            $ownerUser = UserFactory::random() ?? UserFactory::createOne()->object();
            $assignedUserIds[$ownerUser->getId() ?? spl_object_id($ownerUser)] = true;

            MembershipFactory::createOne([
                'team' => $team,
                'member' => $ownerUser,
                'role' => ['OWNER'],
            ]);

            // Créer les autres membres
            $remaining = max(0, $nbMembers - 1);

            for ($i = 0; $i < $remaining; ++$i) {
                // Choisir un utilisateur non encore assigné
                $user = UserFactory::random();
                if ($user) {
                    $uid = $user->getId() ?? spl_object_id($user);
                    if (isset($assignedUserIds[$uid])) {
                        $user = null; // forcer creation
                    }
                } else {
                    $user = UserFactory::createOne()->object();
                }

                $assignedUserIds[$user->getId() ?? spl_object_id($user)] = true;

                // Déterminer le rôle selon le type de team
                if ($team instanceof TeamPlayer) {
                    $role = ['MEMBER'];
                } elseif ($team instanceof TeamCreator) {
                    $roles = ['MODERATOR', 'DEVELOPER', 'TESTER'];
                    $role = [$roles[array_rand($roles)]];
                } else {
                    // fallback
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
