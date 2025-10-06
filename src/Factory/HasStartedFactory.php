<?php

namespace App\Factory;

use App\Entity\HasStarted;
use Zenstruck\Foundry\Persistence\PersistentProxyObjectFactory;

/**
 * @extends PersistentProxyObjectFactory<HasStarted>
 */
final class HasStartedFactory extends PersistentProxyObjectFactory
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
        return HasStarted::class;
    }

    /**
     * @see https://symfony.com/bundles/ZenstruckFoundryBundle/current/index.html#model-factories
     *
     * @todo add your default values here
     */
    protected function defaults(): array|callable
    {
        return [
            'player' => UserFactory::new(),
            'puzzle' => PuzzleFactory::new(),
            'startedAt' => \DateTimeImmutable::createFromMutable(self::faker()->dateTimeBetween('-7 days', 'now')),
        ];
    }

    /**
     * @see https://symfony.com/bundles/ZenstruckFoundryBundle/current/index.html#initialization
     */
    protected function initialize(): static
    {
        return $this
            ->afterInstantiate(function (HasStarted $hasStarted): void {
                if (!$hasStarted->getStartedAt() instanceof \DateTimeImmutable) {
                    $hasStarted->setStartedAt(
                        new \DateTimeImmutable($hasStarted->getStartedAt()->format('Y-m-d H:i:s'))
                    );
                }
            });
    }
}
