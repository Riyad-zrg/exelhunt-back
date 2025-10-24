<?php

namespace App\DataFixtures;

use App\Factory\UserFactory;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class UserFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        UserFactory::new()->createOne([
            'nickname' => 'admin',
            'password' => 'admin_password',
            'roles' => ['ADMIN', 'STAFF', 'PLAYER', 'CONCEPTOR', 'USER'],
            'firstname' => 'Super',
            'lastname' => 'Admin',
            'email' => 'admin@example.local',
        ]);

        UserFactory::createMany(3, function () {
            return [
                'roles' => ['STAFF', 'PLAYER', 'CONCEPTOR', 'USER'],
            ];
        });

        UserFactory::createMany(16, function () {
            return [
                'roles' => ['CONCEPTOR', 'PLAYER', 'USER'],
            ];
        });

        UserFactory::createMany(60, function () {
            return [
                'roles' => ['PLAYER', 'USER'],
            ];
        });

        UserFactory::createMany(20, function () {
            return [
                'roles' => ['GUEST'],
                'firstname' => null,
                'lastname' => null,
                'email' => null,
                'biography' => null,
                'address' => null,
            ];
        });

        $manager->flush();
    }
}
