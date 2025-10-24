<?php

namespace App\Factory;

use App\Entity\Code;
use Faker\Factory;
use Random\RandomException;
use Zenstruck\Foundry\Persistence\PersistentProxyObjectFactory;

/**
 * @extends PersistentProxyObjectFactory<Code>
 */
final class CodeFactory extends PersistentProxyObjectFactory
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
        return Code::class;
    }

    /**
     * @see https://symfony.com/bundles/ZenstruckFoundryBundle/current/index.html#model-factories
     *
     * @todo add your default values here
     * @throws RandomException
     */
    protected function defaults(): array|callable
    {
        $faker = Factory::create('fr_FR');

        // code à 6 chiffres unique
        $code = $faker->unique()->numerify('######');

        $createdAt = new \DateTimeImmutable();

        $days = random_int(1, 30);
        $hours = random_int(0, 23);
        $expireAt = (new \DateTime())->modify('+'.$days.' days +'.$hours.' hours');

        // 60% pour une chasse, 40% pour une équipe (si les factories existent)
        $forTeam = $faker->boolean(40);

        $hunt = null;
        $teamPlayer = null;

        if ($forTeam && class_exists(TeamPlayerFactory::class)) {
            $teamPlayer = TeamPlayerFactory::new();
        } elseif (class_exists(HuntFactory::class)) {
            $hunt = HuntFactory::new();
        }

        return [
            'code' => $code,
            'createdAt' => $createdAt,
            'expireAt' => $expireAt,
            'hunt' => $hunt,
            'teamPlayer' => $teamPlayer,
        ];
    }

    /**
     * @see https://symfony.com/bundles/ZenstruckFoundryBundle/current/index.html#initialization
     */
    protected function initialize(): static
    {
        return $this
            // ->afterInstantiate(function(Code $code): void {})
        ;
    }
}
