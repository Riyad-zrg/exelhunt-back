<?php

namespace App\Factory;

use App\Entity\Puzzle;
use Zenstruck\Foundry\Persistence\PersistentProxyObjectFactory;

/**
 * @extends PersistentProxyObjectFactory<Puzzle>
 */
final class PuzzleFactory extends PersistentProxyObjectFactory
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
        return Puzzle::class;
    }

    /**
     * @see https://symfony.com/bundles/ZenstruckFoundryBundle/current/index.html#model-factories
     *
     * @todo add your default values here
     */
    protected function defaults(): array|callable
    {
        $faker = self::faker();

        return [
            'title' => $faker->sentence(5, true),
            'content' => $faker->paragraph(3, true),
            'hint' => $faker->boolean(70) ? $faker->sentence(8) : null,
            'index' => 0,
            'timeLimit' => $faker->boolean(50) ? (new \DateTime())->setTime(0, $faker->numberBetween(5, 60)) : null,
            'malus' => $faker->boolean(40) ? (new \DateTime())->setTime(0, $faker->numberBetween(1, 15)) : null,
            'hunt' => HuntFactory::random(),
        ];
    }

    protected function initialize(): static
    {
        return $this
            ->afterInstantiate(function (Puzzle $puzzle): void {
                $hunt = $puzzle->getHunt();
                if ($hunt) {
                    $index = count($hunt->getPuzzles()) + 1;
                    $puzzle->setIndex($index);
                }
            });
    }
}
