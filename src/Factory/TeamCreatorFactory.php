<?php

namespace App\Factory;

use App\Entity\TeamCreator;
use Zenstruck\Foundry\Persistence\PersistentProxyObjectFactory;

/**
 * @extends PersistentProxyObjectFactory<TeamCreator>
 */
final class TeamCreatorFactory extends PersistentProxyObjectFactory
{
    /**
     * @see https://symfony.com/bundles/ZenstruckFoundryBundle/current/index.html#factories-as-services
     *
     * @todo inject services if required
     */
    public function __construct()
    {
        parent::__construct();
    }

    public static function class(): string
    {
        return TeamCreator::class;
    }

    /**
     * @see https://symfony.com/bundles/ZenstruckFoundryBundle/current/index.html#model-factories
     *
     * @todo add your default values here
     */
    protected function defaults(): array|callable
    {
        $defaultPath = __DIR__.'/../DataFixtures/img/teamDefault.png';
        if (is_readable($defaultPath)) {
            $avatarData = base64_encode(file_get_contents($defaultPath));
        } else {
            $avatarData = '';
        }

        return [
            'name' => self::faker()->company(),
            'avatar' => $avatarData,
            'createdAt' => \DateTimeImmutable::createFromMutable(self::faker()->dateTimeBetween('-1 years', 'now')),
        ];
    }

    /**
     * @see https://symfony.com/bundles/ZenstruckFoundryBundle/current/index.html#initialization
     */
    protected function initialize(): static
    {
        return $this;
    }
}
