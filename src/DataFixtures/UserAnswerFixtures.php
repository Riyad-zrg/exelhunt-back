<?php

namespace App\DataFixtures;

use App\Entity\UserAnswer;
use App\Factory\PuzzleFactory;
use App\Factory\UserAnswerFactory;
use App\Factory\UserFactory;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class UserAnswerFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager): void
    {
        $puzzles = array_map(fn ($proxy) => $proxy->_real(), PuzzleFactory::all());
        $users = array_map(fn ($proxy) => $proxy->_real(), UserFactory::all());

        if (empty($puzzles) || empty($users)) {
            return;
        }

        $globalPlayerUsers = array_filter($users, fn ($u) => in_array('ROLE_PLAYER', $u->getRoles(), true));

        foreach ($puzzles as $puzzle) {
            $hunt = $puzzle->getHunt();
            $participants = [];
            if ($hunt) {
                $parts = $manager->getRepository(\App\Entity\Participation::class)->findBy(['hunt' => $hunt]);
                foreach ($parts as $part) {
                    if ($part->getPlayer()) {
                        $participants[] = $part->getPlayer();
                    }
                }
            }

            $candidatePlayers = !empty($participants) ? $participants : $globalPlayerUsers;

            if (empty($candidatePlayers)) {
                continue;
            }

            $count = rand(0, min(5, count($candidatePlayers)));
            if (0 === $count) {
                continue;
            }

            $players = $this->getRandomElements($candidatePlayers, $count);

            foreach ($players as $player) {
                $exists = $manager->getRepository(UserAnswer::class)->findOneBy([
                    'player' => $player,
                    'puzzle' => $puzzle,
                ]);

                if ($exists) {
                    continue;
                }

                UserAnswerFactory::createOne([
                    'player' => $player,
                    'puzzle' => $puzzle,
                ]);
            }
        }

        $manager->flush();
    }

    public function getDependencies(): array
    {
        return [
            PuzzleFixtures::class,
            UserFixtures::class,
            ParticipationFixtures::class,
        ];
    }

    private function getRandomElements(array $array, int $count): array
    {
        if ($count >= count($array)) {
            return $array;
        }

        $keys = array_rand($array, $count);
        if (!is_array($keys)) {
            $keys = [$keys];
        }

        return array_map(fn ($key) => $array[$key], $keys);
    }
}
