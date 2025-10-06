<?php

namespace App\Factory;

use App\Entity\Hunt;
use Zenstruck\Foundry\Persistence\PersistentProxyObjectFactory;

/**
 * @extends PersistentProxyObjectFactory<Hunt>
 */
final class HuntFactory extends PersistentProxyObjectFactory
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
        return Hunt::class;
    }

    /**
     * @see https://symfony.com/bundles/ZenstruckFoundryBundle/current/index.html#model-factories
     *
     * @todo add your default values here
     */
    protected function defaults(): array|callable
    {
        $faker = \Faker\Factory::create('fr_FR');

        return [
            'avatar' => base64_encode(file_get_contents(__DIR__.'/../DataFixtures/img/huntIcon.jpg')),
            'createdAt' => \DateTimeImmutable::createFromMutable($faker->dateTimeBetween('-50 days', 'now')),
            'description' => self::faker()->text(500),
            'location' => AddressFactory::new(),
            'nbPlayers' => self::faker()->randomNumber(3, false),
            'title' => self::faker()->sentence(3, true),
            'visibility' => self::faker()->randomElement(['PUBLIC', 'PRIVATE', 'DEVELOPMENT', 'CLOSED']),
            'createdBy' => TeamFactory::new(),
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
