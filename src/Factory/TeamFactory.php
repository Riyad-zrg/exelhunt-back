<?php

namespace App\Factory;

use App\Entity\Team;
use Faker\Factory;
use Zenstruck\Foundry\Persistence\PersistentProxyObjectFactory;

/**
 * @extends PersistentProxyObjectFactory<Team>
 */
final class TeamFactory extends PersistentProxyObjectFactory
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
        return Team::class;
    }

    /**
     * @see https://symfony.com/bundles/ZenstruckFoundryBundle/current/index.html#model-factories
     *
     * @todo add your default values here
     */
    protected function defaults(): array|callable
    {
        return [
            'avatar' => base64_encode(file_get_contents(__DIR__.'/../DataFixtures/img/teamDefault.jpeg')),
            'createdAt' => \DateTimeImmutable::createFromMutable(self::faker()->dateTimeBetween('-30 days', 'now')),
            'name' => self::faker()->company(),
        ];
    }


    /**
     * @see https://symfony.com/bundles/ZenstruckFoundryBundle/current/index.html#initialization
     */
    protected function initialize(): static
    {
        return $this
            // ->afterInstantiate(function(Team $team): void {})
        ;
    }
}
