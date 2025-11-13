<?php

namespace App\Factory;

use App\Entity\Participation;
use Zenstruck\Foundry\Persistence\PersistentProxyObjectFactory;

/**
 * @extends PersistentProxyObjectFactory<Participation>
 */
final class ParticipationFactory extends PersistentProxyObjectFactory
{
    public static function class(): string
    {
        return Participation::class;
    }

    protected function defaults(): array|callable
    {
        $faker = self::faker();

        $hunt = HuntFactory::randomOrCreate();
        $player = UserFactory::randomOrCreate();

        $pool = array_merge(
            array_fill(0, 25, 'pending'),
            array_fill(0, 35, 'in_progress'),
            array_fill(0, 10, 'paused'),
            array_fill(0, 15, 'done'),
            array_fill(0, 4, 'aborted'),
            array_fill(0, 4, 'abandoned'),
            array_fill(0, 3, 'timeout'),
            array_fill(0, 2, 'blocked'),
            array_fill(0, 2, 'disqualified')
        );
        $tracking = $faker->randomElement($pool);

        $teamPlayer = null;
        if (class_exists(TeamPlayerFactory::class) && $faker->boolean(30)) {
            // Fix: Simply use _real() to get the entity from the proxy
            $teamPlayer = TeamPlayerFactory::randomOrCreate(['hunt' => $hunt])->_real();
        }

        $globalTime = null;
        $puzzles = $hunt->getPuzzles()->toArray();
        if (!empty($puzzles) && $faker->boolean(60)) {
            $minutes = rand(5, 180);
            $globalTime = (new \DateTime())->modify(sprintf('-%d minutes', $minutes));
        }

        return [
            'hunt' => $hunt,
            'player' => $player,
            'tracking' => $tracking,
            'globalTime' => $globalTime,
            'teamPlayer' => $teamPlayer,
        ];
    }

    protected function initialize(): static
    {
        return $this
            ->afterInstantiate(function (Participation $participation): void {
            });
    }
}
