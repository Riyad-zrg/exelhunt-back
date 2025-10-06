<?php

namespace App\Factory;

use App\Entity\Membership;
use Zenstruck\Foundry\Persistence\PersistentProxyObjectFactory;

/**
 * @extends PersistentProxyObjectFactory<Membership>
 */
final class MembershipFactory extends PersistentProxyObjectFactory
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
        return Membership::class;
    }

    /**
     * @see https://symfony.com/bundles/ZenstruckFoundryBundle/current/index.html#model-factories
     *
     * @todo add your default values here
     */
    protected function defaults(): array|callable
    {
        return [
            'member' => UserFactory::new(),
            'team' => TeamFactory::new(),
            'role' => ['TESTER'],
            'joinedAt' => \DateTimeImmutable::createFromMutable(self::faker()->dateTimeBetween('-1 year', 'now')),
        ];
    }

    /**
     * @see https://symfony.com/bundles/ZenstruckFoundryBundle/current/index.html#initialization
     */
    protected function initialize(): static
    {
        return $this->afterInstantiate(function (Membership $membership) {
            $roleHierarchy = [
                'OWNER' => ['OWNER', 'MODERATOR', 'DEVELOPER', 'TESTER'],
                'MODERATOR' => ['MODERATOR', 'DEVELOPER', 'TESTER'],
                'DEVELOPER' => ['DEVELOPER', 'TESTER'],
                'TESTER' => ['TESTER'],
            ];
            $roles = $membership->getRole();
            if (count($roles) > 0) {
                $mainRole = strtoupper($roles[0]);
                if (isset($roleHierarchy[$mainRole])) {
                    $membership->getMember()->setRoles($roleHierarchy[$mainRole]);
                }
            }
        });
    }
}
