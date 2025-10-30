<?php

namespace App\Factory;

use App\Entity\Puzzle;
use Zenstruck\Foundry\Persistence\PersistentProxyObjectFactory;

/**
 * @extends PersistentProxyObjectFactory<Puzzle>
 */
final class PuzzleFactory extends PersistentProxyObjectFactory
{
    public static function class(): string
    {
        return Puzzle::class;
    }

    /**
     * @see https://symfony.com/bundles/ZenstruckFoundryBundle/current/index.html#model-factories
     *
     * Génère des données réalistes et cohérentes selon le type d'énigme : QRO, QCM, QR, GPS.
     */
    protected function defaults(): array|callable
    {
        $faker = self::faker();

        $type = $faker->randomElement(['QRO', 'QCM', 'QR', 'GPS']);

        $question = match ($type) {
            'QRO' => $faker->sentence(8, true),
            'QCM' => $faker->sentence(8, true),
            'QR' => 'Scannez le QR code spécifique à cet emplacement pour valider cette étape.',
            'GPS' => 'Rendez-vous à l\'emplacement indiqué et entrez dans le périmètre pour valider cette étape.',
        };

        $content = [];

        if ('QCM' === $type) {
            $numOptions = $faker->numberBetween(3, 5);
            $options = [];
            for ($i = 0; $i < $numOptions; ++$i) {
                $options[] = $faker->sentence(3, true);
            }
            $correctCount = $faker->boolean(80) ? 1 : $faker->numberBetween(1, min(2, $numOptions));
            $correctIndices = $faker->randomElements(range(0, $numOptions - 1), $correctCount);

            $content = [
                'options' => $options,
                'correct' => $correctIndices,
                'multiple' => $correctCount > 1,
            ];
        } elseif ('QRO' === $type) {
            $accepted = [$faker->words($faker->numberBetween(1, 3), true)];
            $content = [
                'acceptedAnswers' => $accepted,
                'caseSensitive' => false,
                'minLength' => 1,
            ];
        } elseif ('QR' === $type) {
            $content = [
                'code' => 'QR-'.strtoupper($faker->bothify('??-#####')),
            ];
        } else { // GPS
            $lat = $faker->latitude(43, 50);
            $lng = $faker->longitude(-1, 7);
            $content = [
                'lat' => (float) $lat,
                'lng' => (float) $lng,
                'radius' => $faker->numberBetween(20, 150),
            ];
        }

        $defaultPath = __DIR__.'/../DataFixtures/img/puzzleDefault.png';
        if (is_readable($defaultPath)) {
            $mediaData = base64_encode(file_get_contents($defaultPath));
        } else {
            $mediaData = '';
        }

        return [
            'title' => ucfirst($faker->words(3, true)),
            'question' => $question,
            'typeAnswer' => $type,
            'contentAnswerJSON' => $content,
            'hint' => $faker->boolean(60) ? $faker->sentence(8) : null,
            'timeLimit' => $faker->boolean(40) ? (new \DateTime())->setTime(0, $faker->numberBetween(1, 60)) : null,
            'malus' => $faker->boolean(30) ? (new \DateTime())->setTime(0, $faker->numberBetween(1, 30)) : null,
            'media' => $mediaData,
            'index' => 0,
            'hunt' => HuntFactory::random(),
        ];
    }

    protected function initialize(): static
    {
        return $this
            ->afterInstantiate(function (Puzzle $puzzle): void {
                $hunt = $puzzle->getHunt();
                if ($hunt) {
                    $index = count($hunt->getPuzzles()) + 1;
                    $puzzle->setIndex($index);
                }
            });
    }
}
