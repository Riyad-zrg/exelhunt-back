<?php

namespace App\Factory;

use App\Entity\Membership;
use Zenstruck\Foundry\Persistence\PersistentProxyObjectFactory;

/**
 * @extends PersistentProxyObjectFactory<Membership>
 */
final class MembershipFactory extends PersistentProxyObjectFactory
{
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
        $faker = self::faker();

        return [
            'member' => UserFactory::new(),
            'team' => TeamPlayerFactory::new(),
            'role' => ['MEMBER'],
            'joinedAt' => \DateTimeImmutable::createFromMutable($faker->dateTimeBetween('-2 years', 'now')),
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
                'MEMBER' => ['MEMBER'],
            ];
            $roles = $membership->getRole();
            if (count($roles) > 0) {
                $mainRole = strtoupper($roles[0]);
                if (isset($roleHierarchy[$mainRole]) && $membership->getMember()) {
                    $membership->getMember()->setRoles($roleHierarchy[$mainRole]);
                }
            }
        });
    }
}
