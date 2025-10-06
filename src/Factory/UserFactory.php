<?php

namespace App\Factory;

use App\Entity\User;
use Faker\Factory;
use Zenstruck\Foundry\Persistence\PersistentProxyObjectFactory;

/**
 * @extends PersistentProxyObjectFactory<User>
 */
final class UserFactory extends PersistentProxyObjectFactory
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
        return User::class;
    }

    /**
     * @see https://symfony.com/bundles/ZenstruckFoundryBundle/current/index.html#model-factories
     *
     * @todo add your default values here
     */
    protected function defaults(): array|callable
    {
        $faker = Factory::create('fr_FR');
        $primaryRole = $faker->randomElement(['GUEST', 'ADMIN', 'STAFF', 'PLAYER', 'USER']);

        // Déterminer les rôles effectifs selon la règle
        $roles = match ($primaryRole) {
            'GUEST' => ['GUEST'],
            'ADMIN' => ['ADMIN', 'STAFF', 'PLAYER', 'CONCEPTOR', 'USER'],
            'STAFF' => ['STAFF', 'PLAYER', 'CONCEPTOR', 'USER'],
            'CONCEPTOR' => ['CONCEPTOR', 'PLAYER', 'USER'],
            'PLAYER' => ['PLAYER', 'USER'],
            'USER' => ['USER'],
        };

        $defaults = [
            'avatar' => base64_encode(file_get_contents(__DIR__.'/../DataFixtures/img/userDefault.jpg')),
            'createdAt' => \DateTimeImmutable::createFromMutable($faker->dateTimeBetween('-500 days', 'now')),
            'nickname' => $faker->userName(),
            'password' => $faker->password(30),
            'roles' => $roles,
        ];

        // Remplir les champs optionnels si ce n'est pas un GUEST
        if ('GUEST' !== $primaryRole) {
            $defaults = array_merge($defaults, [
                'firstname' => $faker->firstName(),
                'lastname' => $faker->lastName(),
                'email' => $faker->email(),
                'biography' => $faker->paragraph(),
                'Address' => AddressFactory::new(),
            ]);
        }

        return $defaults;
    }

    /**
     * @see https://symfony.com/bundles/ZenstruckFoundryBundle/current/index.html#initialization
     */
    protected function initialize(): static
    {
        return $this
            // ->afterInstantiate(function(User $user): void {})
        ;
    }
}
