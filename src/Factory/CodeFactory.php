<?php

namespace App\Factory;

use App\Entity\Code;
use Faker\Factory;
use Zenstruck\Foundry\Persistence\PersistentProxyObjectFactory;

/**
 * @extends PersistentProxyObjectFactory<Code>
 */
final class CodeFactory extends PersistentProxyObjectFactory
{
    public static function class(): string
    {
        return Code::class;
    }

    /**
     * @throws \Exception
     */
    protected function defaults(): array|callable
    {
        $faker = Factory::create('fr_FR');

        $code = $faker->numerify('######');
        $createdAt = new \DateTime();
        $expireAt = (new \DateTime())->modify('+'.random_int(1, 30).' days +'.random_int(0, 23).' hours');

        return [
            'code' => $code,
            'createdAt' => $createdAt,
            'expireAt' => $expireAt,
            'hunt' => HuntFactory::random(),
            'teamPlayer' => null,
        ];
    }

    protected function initialize(): static
    {
        return $this
            ->afterInstantiate(function (Code $entity): void {
                $c = $entity->getCode();
                if (null !== $c) {
                    $c = preg_replace('/[^0-9]/', '', (string) $c);
                    if (strlen($c) < 6) {
                        $c = str_pad($c, 6, '0', STR_PAD_LEFT);
                    } elseif (strlen($c) > 6) {
                        $c = substr($c, 0, 6);
                    }
                    $entity->setCode((int) $c);
                } else {
                    $entity->setCode((int) str_pad((string) random_int(0, 999999), 6, '0', STR_PAD_LEFT));
                }

                $createdAt = $entity->getCreatedAt() ?? new \DateTime();
                $expireAt = $entity->getExpireAt() ?? (new \DateTime())->modify('+7 days');

                if ($expireAt <= $createdAt) {
                    $newExpire = clone $createdAt;
                    $newExpire->modify('+'.random_int(1, 30).' days +'.random_int(0, 23).' hours');
                    $entity->setExpireAt($newExpire);
                } else {
                    $entity->setExpireAt($expireAt);
                }

                $entity->setCreatedAt($createdAt);
            });
    }
}
