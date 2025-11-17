<?php

namespace App\Factory;

use App\Entity\UserAnswer;
use Random\RandomException;
use Zenstruck\Foundry\Persistence\PersistentProxyObjectFactory;

/**
 * @extends PersistentProxyObjectFactory<UserAnswer>
 */
final class UserAnswerFactory extends PersistentProxyObjectFactory
{
    public static function class(): string
    {
        return UserAnswer::class;
    }

    /**
     * @throws RandomException
     */
    protected function defaults(): array|callable
    {
        $faker = self::faker();

        $puzzle = PuzzleFactory::randomOrCreate();
        $player = UserFactory::randomOrCreate();

        $content = [];
        $isCorrect = false;

        $earliest = new \DateTimeImmutable('-30 days');
        $sendAtTs = random_int($earliest->getTimestamp(), (new \DateTimeImmutable('now'))->getTimestamp());
        $sendAt = (new \DateTimeImmutable())->setTimestamp($sendAtTs);

        return [
            'answerContent' => $content,
            'isCorrect' => false,
            'sendAt' => $sendAt,
            'player' => $player,
            'puzzle' => $puzzle,
        ];
    }

    protected function initialize(): static
    {
        return $this
            ->afterInstantiate(function (UserAnswer $userAnswer): void {
                $faker = self::faker();
                $puzzle = $userAnswer->getPuzzle();

                if (!$puzzle) {
                    return;
                }

                $type = trim($puzzle->getTypeAnswer());
                $puzzleContent = $puzzle->getAnswerContent();

                $isCorrect = false;

                switch (strtoupper($type)) {
                    case 'QCM':
                        $options = $puzzleContent['options'] ?? [];
                        $numOptions = count($options);
                        if (0 === $numOptions) {
                            $content = ['selected' => []];
                        } else {
                            $multiple = $puzzleContent['multiple'] ?? false;
                            $correct = $puzzleContent['correct'] ?? [];

                            if ($faker->boolean(70)) {
                                $selected = $correct;
                                $isCorrect = true;
                            } else {
                                if ($multiple) {
                                    $selectedCount = $faker->numberBetween(1, max(1, min(3, $numOptions)));
                                    $selected = $faker->randomElements(range(0, $numOptions - 1), $selectedCount);
                                } else {
                                    $selected = [$faker->numberBetween(0, $numOptions - 1)];
                                }
                                $isCorrect = !empty(array_intersect($selected, $correct)) && empty(array_diff($selected, $correct));
                            }
                            $content = ['selected' => $selected];
                        }
                        break;

                    case 'QRO':
                        $accepted = $puzzleContent['acceptedAnswers'] ?? [];
                        if (!empty($accepted) && $faker->boolean(70)) {
                            $answer = $faker->randomElement($accepted);
                            $content = ['answer' => $answer];
                            $isCorrect = true;
                        } else {
                            $answer = $faker->sentence(3);
                            $content = ['answer' => $answer];
                        }
                        break;

                    case 'QR':
                        $expectedCode = $puzzleContent['code'] ?? 'QR-CODE-'.strtoupper($faker->bothify('??##??##'));
                        if ($faker->boolean(70)) {
                            $content = ['code' => $expectedCode];
                            $isCorrect = true;
                        } else {
                            $content = ['code' => 'QR-CODE-'.strtoupper($faker->bothify('??##??##'))];
                        }
                        break;

                    case 'GPS':
                        $expectedLat = $puzzleContent['lat'] ?? $faker->latitude();
                        $expectedLng = $puzzleContent['lng'] ?? $faker->longitude();
                        $radius = $puzzleContent['radius'] ?? 50;

                        if ($faker->boolean(70)) {
                            $offsetLat = $faker->randomFloat(6, -0.0001, 0.0001) * ($radius / 50);
                            $offsetLng = $faker->randomFloat(6, -0.0001, 0.0001) * ($radius / 50);
                            $content = [
                                'lat' => $expectedLat + $offsetLat,
                                'lng' => $expectedLng + $offsetLng,
                            ];
                            $isCorrect = true;
                        } else {
                            $content = [
                                'lat' => $faker->latitude(),
                                'lng' => $faker->longitude(),
                            ];
                        }
                        break;

                    default:
                        $content = ['answer' => $faker->sentence(3)];
                        $isCorrect = $faker->boolean(30);
                        break;
                }

                $userAnswer->setAnswerContent($content);
                $userAnswer->setIsCorrect($isCorrect);
            })
        ;
    }
}
