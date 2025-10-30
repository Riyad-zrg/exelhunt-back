<?php

namespace App\Factory;

use App\Entity\TeamPlayer;
use Zenstruck\Foundry\Persistence\PersistentProxyObjectFactory;

/**
 * @extends PersistentProxyObjectFactory<TeamPlayer>
 */
final class TeamPlayerFactory extends PersistentProxyObjectFactory
{
    public static function class(): string
    {
        return TeamPlayer::class;
    }

    /**
     * @see https://symfony.com/bundles/ZenstruckFoundryBundle/current/index.html#model-factories
     *
     * @todo add your default values here
     */
    protected function defaults(): array|callable
    {
        $faker = self::faker();

        $avatarPath = __DIR__.'/../DataFixtures/img/teamDefault.png';
        $avatarData = is_readable($avatarPath) ? base64_encode(file_get_contents($avatarPath)) : '';

        return [
            'name' => ucfirst($faker->words(2, true)).' Team',
            'avatar' => $avatarData,
            'hunt' => null,
            'isPublic' => $faker->boolean(70),
            'nbPlayers' => $faker->numberBetween(1, 4),
        ];
    }

    /**
     * @see https://symfony.com/bundles/ZenstruckFoundryBundle/current/index.html#initialization
     */
    protected function initialize(): static
    {
        return $this
            ->afterInstantiate(function (TeamPlayer $teamPlayer): void {
                $hunt = $teamPlayer->getHunt();
                if (null !== $hunt) {
                    $huntMax = $hunt->getNbPlayers() ?? 4;
                    $nb = $teamPlayer->getNbPlayers() ?? 1;
                    $nb = max(1, min($nb, $huntMax));
                    $teamPlayer->setNbPlayers($nb);
                } else {
                    $teamPlayer->setNbPlayers(max(1, $teamPlayer->getNbPlayers() ?? 1));
                }

                if (false === $teamPlayer->isPublic() && class_exists(CodeFactory::class)) {
                    $reference = null;
                    if (method_exists($teamPlayer, 'getCreatedAt') && null !== $teamPlayer->getCreatedAt()) {
                        $reference = $teamPlayer->getCreatedAt();
                    } elseif (null !== $hunt && method_exists($hunt, 'getCreatedAt') && null !== $hunt->getCreatedAt()) {
                        $reference = $hunt->getCreatedAt();
                    }

                    if ($reference instanceof \DateTimeInterface) {
                        $seconds = random_int(1, 3600);
                        if ($reference instanceof \DateTimeImmutable) {
                            $createdAt = $reference->modify('+'.$seconds.' seconds');
                        } else {
                            $createdAt = \DateTimeImmutable::createFromMutable($reference)->modify('+'.$seconds.' seconds');
                        }
                    } else {
                        $createdAt = new \DateTimeImmutable();
                    }

                    $code = CodeFactory::createOne(['createdAt' => $createdAt])->object();
                    $teamPlayer->setCode($code);
                }
            })
        ;
    }
}
