<?php

namespace App\Factory;

use App\Entity\UserAnswer;
use Zenstruck\Foundry\Persistence\PersistentProxyObjectFactory;

/**
 * @extends PersistentProxyObjectFactory<UserAnswer>
 */
final class UserAnswerFactory extends PersistentProxyObjectFactory
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
        return UserAnswer::class;
    }

    /**
     * @see https://symfony.com/bundles/ZenstruckFoundryBundle/current/index.html#model-factories
     *
     * @todo add your default values here
     */
    protected function defaults(): array|callable
    {
        $faker = self::faker();
        $puzzleAnswer = PuzzleAnswerFactory::random();

        $content = [];
        $isCorrect = $faker->boolean();

        if ($puzzleAnswer) {
            switch ($puzzleAnswer->getType()) {
                case 'qcm':
                    $options = $puzzleAnswer->getContent()['options'] ?? [];
                    $selected = $faker->randomElement($options);
                    $content = ['selected' => $selected['label']];
                    $isCorrect = $selected['isCorrect'];
                    break;

                case 'text':
                    $content = ['answer' => $faker->sentence(3)];
                    break;

                case 'qrcode':
                    $content = ['code' => $faker->uuid()];
                    break;

                case 'gps':
                    $content = [
                        'latitude' => $faker->latitude(),
                        'longitude' => $faker->longitude(),
                    ];
                    break;
            }
        }

        return [
            'content' => $content,
            'isCorrect' => $isCorrect,
            'sendAt' => \DateTimeImmutable::createFromMutable($faker->dateTimeBetween('-10 days', 'now')),
            'puzzleAnswer' => $puzzleAnswer,
        ];
    }

    /**
     * @see https://symfony.com/bundles/ZenstruckFoundryBundle/current/index.html#initialization
     */
    protected function initialize(): static
    {
        return $this
            // ->afterInstantiate(function(UserAnswer $userAnswer): void {})
        ;
    }
}
