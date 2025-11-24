<?php

declare(strict_types=1);

namespace App\Tests\Application;

use App\Entity\User;
use Doctrine\DBAL\Exception\UniqueConstraintViolationException;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Tests\Support\ApplicationTester;

final class UserCest
{
    public function _before(ApplicationTester $I): void
    {
        // Code here will be executed before each test.
    }

    public function userExistsInDatabase(ApplicationTester $I): void
    {
        $I->haveInRepository(User::class, [
            'nickname' => 'killy',
            'password' => 'hashed-password',
            'roles' => ['ROLE_USER'],
            'createdAt' => new \DateTimeImmutable('2025-01-01 00:00:00'),
            'email' => 'killy@example.test',
        ]);

        $I->seeInRepository(User::class, [
            'nickname' => 'killy',
        ]);

        $user = $I->grabEntityFromRepository(User::class, [
            'nickname' => 'killy',
        ]);

        $I->assertNotNull($user->getId());
        $I->assertSame('killy', $user->getNickname());
        $I->assertSame('killy@example.test', $user->getEmail());
    }

    public function userNicknameIsUnique(ApplicationTester $I): void
    {
        $I->haveInRepository(User::class, [
            'nickname' => 'unique_nickname',
            'password' => 'hashed-password',
            'roles' => ['ROLE_USER'],
            'createdAt' => new \DateTimeImmutable(),
            'email' => 'user1@example.test',
        ]);

        try {
            $I->haveInRepository(User::class, [
                'nickname' => 'unique_nickname',
                'password' => 'hashed-password',
                'roles' => ['ROLE_USER'],
                'createdAt' => new \DateTimeImmutable(),
                'email' => 'user2@example.test',
            ]);

            $I->fail('Une contrainte d’unicité sur nickname aurait dû être levée.');
        } catch (UniqueConstraintViolationException $exception) {
        }
    }
}
