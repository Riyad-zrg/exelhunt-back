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

        $teamGlobalTime = null;
        $averageGlobalTime = null;

        if ($faker->boolean(60)) {
            $minutes = $faker->numberBetween(30, 300);
            $teamGlobalTime = (new \DateTime())->setTime(0, $minutes);

            $nbPlayers = $faker->numberBetween(1, 4);
            $averageMinutes = (int) ($minutes / max(1, $nbPlayers));
            $averageGlobalTime = (new \DateTime())->setTime(0, $averageMinutes);
        }

        return [
            'name' => (function () use ($faker) {
                $n = ucfirst($faker->words(2, true)).' Team';

                return mb_strlen($n) > 30 ? mb_substr($n, 0, 30) : $n;
            })(),
            'avatar' => $avatarData,
            'hunt' => HuntFactory::random(),
            'isPublic' => $faker->boolean(70),
            'nbPlayers' => $nbPlayers ?? $faker->numberBetween(1, 4),
            'teamGlobalTime' => $teamGlobalTime,
            'averageGlobalTime' => $averageGlobalTime,
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
            })
        ;
    }
}
