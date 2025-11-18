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
            'QR' => 'Scannez le QR code spécifique à cet emplacement pour valider cette étape.',
            'GPS' => 'Rendez-vous à l\'emplacement indiqué et entrez dans le périmètre pour valider cette étape.',
            default => $faker->sentence(8, true),
        };

        if ('QCM' === $type) {
            $numOptions = $faker->numberBetween(3, 5);
            $options = [];
            for ($i = 0; $i < $numOptions; ++$i) {
                $options[] = $faker->sentence(3, true);
            }
            $correctCount = $faker->boolean(60) ? $faker->numberBetween(2, min(3, $numOptions)) : 1;
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
            $lat = $faker->randomFloat(6, 43, 50);
            $lng = $faker->randomFloat(6, -1, 7);
            $content = [
                'lat' => (float) $lat,
                'lng' => (float) $lng,
                'radius' => $faker->numberBetween(20, 150),
            ];
        }

        $mediaData = null;
        if ($faker->boolean(40)) {
            $defaultPath = __DIR__.'/../DataFixtures/img/puzzleDefault.png';
            if (is_readable($defaultPath)) {
                $mediaData = base64_encode(file_get_contents($defaultPath));
            }
        }

        return [
            'title' => ucfirst($faker->words(3, true)),
            'content' => $faker->sentence(10, true),
            'question' => $question,
            'typeAnswer' => $type,
            'answerContent' => $content,
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
                    $existingPuzzles = $hunt->getPuzzles()->toArray();
                    $maxIndex = 0;
                    foreach ($existingPuzzles as $p) {
                        if ($p !== $puzzle && $p->getIndex() > $maxIndex) {
                            $maxIndex = $p->getIndex();
                        }
                    }
                    $puzzle->setIndex($maxIndex + 1);
                }
            });
    }
}
