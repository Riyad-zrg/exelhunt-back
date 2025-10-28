<?php

namespace App\Factory;

use App\Entity\User;
use Faker\Factory;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Zenstruck\Foundry\Persistence\PersistentProxyObjectFactory;

/**
 * @extends PersistentProxyObjectFactory<User>
 */
final class UserFactory extends PersistentProxyObjectFactory
{
    private UserPasswordHasherInterface $passwordHasher;

    /**
     * @see https://symfony.com/bundles/ZenstruckFoundryBundle/current/index.html#factories-as-services
     *
     * @todo inject services if required
     */
    public function __construct(UserPasswordHasherInterface $passwordHasher)
    {
        $this->passwordHasher = $passwordHasher;
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

        $primaryRole = $faker->randomElement(['GUEST', 'ADMIN', 'STAFF', 'PLAYER', 'CONCEPTOR']);

        $roles = match ($primaryRole) {
            'GUEST' => ['GUEST'],
            'ADMIN' => ['ADMIN', 'STAFF', 'PLAYER', 'CONCEPTOR', 'USER'],
            'STAFF' => ['STAFF', 'PLAYER', 'CONCEPTOR', 'USER'],
            'CONCEPTOR' => ['CONCEPTOR', 'PLAYER', 'USER'],
            'PLAYER' => ['PLAYER', 'USER'],
            default => ['USER'],
        };

        $defaultPath = __DIR__.'/../DataFixtures/img/userDefault.png';
        if (is_readable($defaultPath)) {
            $avatarData = base64_encode(file_get_contents($defaultPath));
        } else {
            $avatarData = '';
        }

        $hasAddress = $faker->boolean(60) ? AddressFactory::new() : null;

        $defaults = [
            'avatar' => $avatarData,
            'createdAt' => \DateTimeImmutable::createFromMutable($faker->dateTimeBetween('-500 days', 'now')),
            'nickname' => $faker->unique()->userName(),
            'password' => $faker->password(12),
            'roles' => $roles,
            'address' => $hasAddress,
        ];

        if ('GUEST' !== $primaryRole) {
            $defaults = array_merge($defaults, [
                'firstname' => $faker->firstName(),
                'lastname' => $faker->lastName(),
                'email' => $faker->unique()->safeEmail(),
                'biography' => $faker->optional(0.9)->paragraph(),
            ]);
        } else {
            $defaults = array_merge($defaults, [
                'firstname' => null,
                'lastname' => null,
                'email' => null,
                'biography' => null,
            ]);
        }

        return $defaults;
    }

    protected function initialize(): static
    {
        return $this
            ->afterInstantiate(function (User $user): void {
                $plain = $user->getPassword();
                if (null !== $plain && '' !== $plain) {
                    $hashed = $this->passwordHasher->hashPassword($user, $plain);
                    $user->setPassword($hashed);
                }

                if ($user->getRoles() === ['GUEST']) {
                    $user->setFirstname(null);
                    $user->setLastname(null);
                    $user->setEmail(null);
                    $user->setBiography(null);
                    $user->setAddress(null);
                }
            })
        ;
    }
}
