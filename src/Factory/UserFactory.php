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
        parent::__construct();
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

        $avatarPath = __DIR__.'/../DataFixtures/img/userDefault.png';
        $avatarData = is_readable($avatarPath) ? base64_encode(file_get_contents($avatarPath)) : '';

        $rolePool = array_merge(
            array_fill(0, 20, ['ROLE_GUEST']),
            array_fill(0, 40, ['ROLE_PLAYER', 'ROLE_USER']),
            array_fill(0, 25, ['ROLE_CONCEPTOR', 'ROLE_PLAYER', 'ROLE_USER']),
            array_fill(0, 10, ['ROLE_STAFF', 'ROLE_PLAYER', 'ROLE_CONCEPTOR', 'ROLE_USER']),
            array_fill(0, 4, ['ROLE_ADMIN', 'ROLE_STAFF', 'ROLE_PLAYER', 'ROLE_CONCEPTOR', 'ROLE_USER']),
            array_fill(0, 1, ['ROLE_ADMIN', 'ROLE_STAFF', 'ROLE_PLAYER', 'ROLE_CONCEPTOR', 'ROLE_USER'])
        );
        $roles = $faker->randomElement($rolePool);
        $isGuest = in_array('ROLE_GUEST', $roles);

        $defaults = [
            'nickname' => $faker->unique()->userName(),
            'password' => 'password123',
            'roles' => $roles,
            'avatar' => $avatarData,
            'createdAt' => \DateTimeImmutable::createFromMutable($faker->dateTimeBetween('-2 years', 'now')),
        ];

        if ($isGuest) {
            $defaults['firstname'] = null;
            $defaults['lastname'] = null;
            $defaults['email'] = null;
            $defaults['biography'] = null;
            $defaults['address'] = null;
        } else {
            $defaults['firstname'] = $faker->firstName();
            $defaults['lastname'] = $faker->lastName();
            $defaults['email'] = $faker->unique()->email();
            $defaults['biography'] = $faker->realText(200);
            $defaults['address'] = AddressFactory::randomOrCreate()->_real();
        }

        return $defaults;
    }

    protected function initialize(): static
    {
        return $this
            ->afterInstantiate(function (User $user): void {
                $faker = Factory::create('fr_FR');

                $plain = $user->getPassword();
                if (null !== $plain && '' !== $plain) {
                    $hashed = $this->passwordHasher->hashPassword($user, $plain);
                    $user->setPassword($hashed);
                }

                $isGuest = in_array('ROLE_GUEST', $user->getRoles());

                if ($isGuest) {
                    $user->setFirstname(null);
                    $user->setLastname(null);
                    $user->setEmail(null);
                    $user->setBiography(null);
                    $user->setAddress(null);
                } else {
                    if (empty($user->getFirstname())) {
                        $user->setFirstname($faker->firstName());
                    }
                    if (empty($user->getLastname())) {
                        $user->setLastname($faker->lastName());
                    }
                    if (empty($user->getEmail())) {
                        $user->setEmail($faker->unique()->email());
                    }
                    if (empty($user->getNickname())) {
                        $user->setNickname($faker->unique()->userName());
                    }
                    if (empty($user->getAvatar())) {
                        $avatarPath = __DIR__.'/../DataFixtures/img/userDefault.png';
                        $user->setAvatar(is_readable($avatarPath) ? base64_encode(file_get_contents($avatarPath)) : '');
                    }
                    if (null === $user->getBiography()) {
                        $user->setBiography($faker->realText(200));
                    }
                    if (null === $user->getAddress()) {
                        $user->setAddress(AddressFactory::randomOrCreate()->_real());
                    }
                }
            })
        ;
    }
}
