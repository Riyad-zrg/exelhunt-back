<?php

namespace App\Factory;

use App\Entity\Code;
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
     */
    protected function defaults(): array|callable
    {
        return [
            'code' => self::faker()->unique()->numerify('######'),
            'createdAt' => \DateTimeImmutable::createFromMutable(self::faker()->dateTimeBetween('-120 days', 'now')),
            'expireAt' => \DateTimeImmutable::createFromMutable(self::faker()->dateTimeBetween('now', '+60 days')),
            'hunt' => HuntFactory::new(),
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
