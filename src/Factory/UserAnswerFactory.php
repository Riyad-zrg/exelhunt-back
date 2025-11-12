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
        $type = $puzzle->getTypeAnswer();
        $puzzleContent = $puzzle->getContentAnswerJSON();

        $player = UserFactory::randomOrCreate();

        $content = [];
        $isCorrect = false;

        switch (strtoupper($type)) {
            case 'QCM':
                $options = $puzzleContent['options'] ?? [];
                $numOptions = count($options);
                if (0 === $numOptions) {
                    $content = ['selected' => []];
                } else {
                    $multiple = $puzzleContent['multiple'] ?? false;
                    if ($multiple) {
                        $selectedCount = $faker->numberBetween(1, max(1, min(3, $numOptions)));
                        $selected = $faker->randomElements(range(0, $numOptions - 1), $selectedCount);
                    } else {
                        $selected = [$faker->numberBetween(0, $numOptions - 1)];
                    }
                    $content = ['selected' => $selected];

                    $correct = $puzzleContent['correct'] ?? [];
                    $isCorrect = !empty(array_intersect($selected, $correct)) && empty(array_diff($selected, $correct));
                }
                break;

            case 'QRO':
            case 'QRO ':
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
            case 'QRCODE':
                $code = $puzzleContent['code'] ?? $faker->bothify('QR-??-#####');
                if ($faker->boolean(85)) {
                    $content = ['code' => $code];
                    $isCorrect = true;
                } else {
                    $content = ['code' => 'WRONG-'.$faker->bothify('??-#####')];
                }
                break;

            case 'GPS':
                $lat = $puzzleContent['lat'] ?? $faker->latitude(43, 50);
                $lng = $puzzleContent['lng'] ?? $faker->longitude(-1, 7);
                $radius = $puzzleContent['radius'] ?? 50;

                if ($faker->boolean(80)) {
                    $delta = $radius / 111000.0; // approx degrees
                    $content = [
                        'latitude' => $lat + $faker->randomFloat(6, -$delta, $delta),
                        'longitude' => $lng + $faker->randomFloat(6, -$delta, $delta),
                    ];
                    $isCorrect = true;
                } else {
                    $content = [
                        'latitude' => $lat + $faker->numberBetween(1, 10),
                        'longitude' => $lng + $faker->numberBetween(1, 10),
                    ];
                }
                break;

            default:
                $content = ['answer' => $faker->sentence(3)];
                $isCorrect = (bool) $faker->boolean(50);
                break;
        }

        $earliest = new \DateTimeImmutable('-30 days');
        if ($puzzle->getHunt() && $puzzle->getHunt()->getCreatedAt() instanceof \DateTimeInterface) {
            $earliest = \DateTimeImmutable::createFromMutable(\DateTime::createFromFormat('Y-m-d H:i:s', $puzzle->getHunt()->getCreatedAt()->format('Y-m-d H:i:s')));
        }

        $sendAtTs = random_int($earliest->getTimestamp(), (new \DateTimeImmutable('now'))->getTimestamp());
        $sendAt = (new \DateTimeImmutable())->setTimestamp($sendAtTs);

        return [
            'contentAnswerJSON' => $content,
            'isCorrect' => $isCorrect,
            'sendAt' => $sendAt,
            'player' => $player,
            'puzzle' => $puzzle,
        ];
    }

    protected function initialize(): static
    {
        return $this
            // ->afterInstantiate(function(UserAnswer $userAnswer): void {})
        ;
    }
}
