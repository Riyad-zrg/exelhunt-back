<?php

namespace App\Factory;

use App\Entity\Code;
use Faker\Factory;
use Random\RandomException;
use Zenstruck\Foundry\Persistence\PersistentProxyObjectFactory;

/**
 * @extends PersistentProxyObjectFactory<Code>
 */
final class CodeFactory extends PersistentProxyObjectFactory
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
        return Code::class;
    }

    /**
     * @see https://symfony.com/bundles/ZenstruckFoundryBundle/current/index.html#model-factories
     *
     * @todo add your default values here
     * @throws RandomException
     */
    protected function defaults(): array|callable
    {
        $faker = Factory::create('fr_FR');

        return [
            'code' => $faker->unique()->numerify('######'),
            'createdAt' => new \DateTimeImmutable(),
            'expireAt' => (new \DateTime())->modify('+'.random_int(1, 30).' days +'.random_int(0, 23).' hours'),
        ];
    }

    /**
     * @see https://symfony.com/bundles/ZenstruckFoundryBundle/current/index.html#initialization
     */
    protected function initialize(): static
    {
        return $this
            // ->afterInstantiate(function(Code $code): void {})
        ;
    }
}
