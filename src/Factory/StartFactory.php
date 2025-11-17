<?php

namespace App\Factory;

use App\Entity\Start;
use Zenstruck\Foundry\Persistence\PersistentProxyObjectFactory;

/**
 * @extends PersistentProxyObjectFactory<Start>
 */
final class StartFactory extends PersistentProxyObjectFactory
{
    public static function class(): string
    {
        return Start::class;
    }

    /**
     * @see https://symfony.com/bundles/ZenstruckFoundryBundle/current/index.html#model-factories
     *
     * @todo add your default values here
     */
    protected function defaults(): array|callable
    {
        return [
            'player' => UserFactory::new(),
            'puzzle' => PuzzleFactory::new(),
            'startedAt' => \DateTimeImmutable::createFromMutable(self::faker()->dateTimeBetween('-7 days', 'now')),
        ];
    }

    /**
     * @see https://symfony.com/bundles/ZenstruckFoundryBundle/current/index.html#initialization
     */
    protected function initialize(): static
    {
        return $this
            ->afterInstantiate(function (Start $start): void {
                $puzzle = $start->getPuzzle();

                $earliest = new \DateTimeImmutable('-30 days');
                if ($puzzle && $puzzle->getHunt() && $puzzle->getHunt()->getCreatedAt() instanceof \DateTimeInterface) {
                    $earliest = \DateTimeImmutable::createFromMutable(
                        \DateTime::createFromFormat('Y-m-d H:i:s', $puzzle->getHunt()->getCreatedAt()->format('Y-m-d H:i:s'))
                    );
                }

                $startedAt = $start->getStartedAt();

                if (!($startedAt instanceof \DateTimeImmutable) || $startedAt < $earliest) {
                    $startTs = $earliest->getTimestamp();
                    $endTs = (new \DateTimeImmutable('now'))->getTimestamp();
                    if ($startTs > $endTs) {
                        $startTs = $endTs;
                    }

                    $randomTs = random_int((int) $startTs, (int) $endTs);
                    $start->setStartedAt((new \DateTimeImmutable())->setTimestamp($randomTs));
                }
            });
    }
}
