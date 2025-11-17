<?php

namespace App\Factory;

use App\Entity\Hunt;
use Faker\Factory;
use Zenstruck\Foundry\Persistence\PersistentProxyObjectFactory;

/**
 * @extends PersistentProxyObjectFactory<Hunt>
 */
final class HuntFactory extends PersistentProxyObjectFactory
{
    public static function class(): string
    {
        return Hunt::class;
    }

    /**
     * @see https://symfony.com/bundles/ZenstruckFoundryBundle/current/index.html#model-factories
     *
     * @todo add your default values here
     */
    protected function defaults(): array|callable
    {
        $faker = Factory::create('fr_FR');

        $avatarPath = __DIR__.'/../DataFixtures/img/huntDefault.png';
        $avatarData = is_readable($avatarPath) ? base64_encode(file_get_contents($avatarPath)) : '';

        $visibility = $faker->randomElement(
            array_merge(
                array_fill(0, 60, 'PUBLIC'),
                array_fill(0, 30, 'PRIVATE'),
                ['DEVELOPMENT', 'CLOSED']
            )
        );

        $nbPlayers = $faker->numberBetween(1, 20);

        $createdAt = \DateTimeImmutable::createFromMutable($faker->dateTimeBetween('-50 days', 'now'));

        $updatedAt = $faker->boolean(20) ? $faker->dateTimeBetween($createdAt->format('Y-m-d H:i:s'), 'now') : null;

        $isTeamPlayable = $nbPlayers >= 2 && $faker->boolean(30);
        $teamPlayerMax = $isTeamPlayable ? $faker->numberBetween(2, min(10, $nbPlayers)) : null;

        return [
            'avatar' => $avatarData,
            'createdAt' => $createdAt,
            'updatedAt' => $updatedAt,
            'description' => self::faker()->realText(300),
            'location' => AddressFactory::new(),
            'nbPlayers' => $nbPlayers,
            'title' => self::faker()->sentence(3, true),
            'visibility' => $visibility,
            'createdBy' => TeamCreatorFactory::new(),
            'isTeamPlayable' => $isTeamPlayable,
            'teamPlayerMax' => $teamPlayerMax,
        ];
    }

    /**
     * @see https://symfony.com/bundles/ZenstruckFoundryBundle/current/index.html#initialization
     */
    protected function initialize(): static
    {
        return $this
            // ->afterInstantiate(function(Hunt $hunt): void {})
        ;
    }
}
