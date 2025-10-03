<?php

namespace App\Factory;

use App\Entity\Participation;
use Zenstruck\Foundry\Persistence\PersistentProxyObjectFactory;

/**
 * @extends PersistentProxyObjectFactory<Participation>
 */
final class ParticipationFactory extends PersistentProxyObjectFactory
{
    /**
     * @see https://symfony.com/bundles/ZenstruckFoundryBundle/current/index.html#factories-as-services
     *
     * @todo inject services if required
     */
    public function __construct()
    {
    }

    public static function class(): string
    {
        return Participation::class;
    }

    /**
     * @see https://symfony.com/bundles/ZenstruckFoundryBundle/current/index.html#model-factories
     *
     * @todo add your default values here
     */
    protected function defaults(): array|callable
    {
        $hunt = HuntFactory::randomOrCreate();
        $player = UserFactory::randomOrCreate();
        $puzzles = $hunt->getPuzzles()->toArray();
        $tracking = null;
        if (!empty($puzzles)) {
            $randomPuzzle = self::faker()->randomElement($puzzles);
            $tracking = 'Puzzle_'.$randomPuzzle->getId();
        }

        return [
            'hunt' => $hunt,
            'player' => $player,
            'tracking' => $tracking ?? 'start',
            'globalTime' => null,
        ];
    }

    protected function initialize(): static
    {
        return $this
            ->afterInstantiate(function (Participation $participation): void {
                if (self::faker()->boolean(20)) {
                    $participation->setGlobalTime(self::faker()->dateTimeBetween('-2 hours', 'now'));
                }
            });
    }
}
