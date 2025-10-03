<?php

namespace App\Factory;

use App\Entity\PuzzleAnswer;
use Zenstruck\Foundry\Persistence\PersistentProxyObjectFactory;

/**
 * @extends PersistentProxyObjectFactory<PuzzleAnswer>
 */
final class PuzzleAnswerFactory extends PersistentProxyObjectFactory
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
        return PuzzleAnswer::class;
    }

    /**
     * @see https://symfony.com/bundles/ZenstruckFoundryBundle/current/index.html#model-factories
     *
     * @todo add your default values here
     */
    protected function defaults(): array|callable
    {
        $faker = self::faker();
        $type = $faker->randomElement(['qcm', 'text', 'qrcode', 'gps']);

        $content = match ($type) {
            'qcm' => $this->generateQcm($faker),
            'text' => [
                'answer' => $faker->sentence(3),
            ],
            'qrcode' => [
                'code' => $faker->uuid(),
            ],
            'gps' => [
                'latitude' => $faker->latitude(),
                'longitude' => $faker->longitude(),
                'radius' => $faker->randomFloat(2, 5, 100),
            ],
            default => [],
        };

        return [
            'type' => $type,
            'content' => $content,
            'puzzle' => PuzzleFactory::random(),
        ];
    }

    private function generateQcm($faker): array
    {
        $options = [];
        $nbOptions = $faker->numberBetween(2, 5);
        $correctAnswers = $faker->randomElements(range(0, $nbOptions - 1), $faker->numberBetween(1, $nbOptions));

        for ($i = 0; $i < $nbOptions; ++$i) {
            $options[] = [
                'label' => $faker->word(),
                'isCorrect' => in_array($i, $correctAnswers),
            ];
        }

        return [
            'question' => $faker->sentence(6),
            'options' => $options,
        ];
    }

    /**
     * @see https://symfony.com/bundles/ZenstruckFoundryBundle/current/index.html#initialization
     */
    protected function initialize(): static
    {
        return $this
            // ->afterInstantiate(function(PuzzleAnswer $puzzleAnswer): void {})
        ;
    }
}
