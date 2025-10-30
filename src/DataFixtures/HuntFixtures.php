<?php

namespace App\DataFixtures;

use App\Entity\TeamCreator;
use App\Factory\HuntFactory;
use App\Factory\PuzzleFactory;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Random\RandomException;

class HuntFixtures extends Fixture
{
    /**
     * @throws RandomException
     */
    public function load(ObjectManager $manager): void
    {
        $visibilities = ['PUBLIC', 'PRIVATE', 'DEVELOPMENT', 'CLOSED'];

        $creators = $manager->getRepository(TeamCreator::class)->findAll();

        $keepVisibility = 'PUBLIC';

        foreach ($visibilities as $visibility) {
            $creator = $creators ? $creators[array_rand($creators)] : null;

            $hunt = HuntFactory::createOne([
                'visibility' => $visibility,
                'createdBy' => $creator,
            ])->object();

            if ($visibility === $keepVisibility) {
                // QRO
                PuzzleFactory::createOne([
                    'hunt' => $hunt,
                    'typeAnswer' => 'QRO',
                    'question' => 'Quel langage de programmation est principalement utilisé pour développer des applications Symfony ?',
                    'contentAnswerJSON' => [
                        'acceptedAnswers' => ['php', 'PHP'],
                        'caseSensitive' => false,
                        'minLength' => 2,
                    ],
                ]);

                // QCM
                PuzzleFactory::createOne([
                    'hunt' => $hunt,
                    'typeAnswer' => 'QCM',
                    'question' => 'Parmi ces éléments, lequel est un système de gestion de bases de données relationnelles ?',
                    'contentAnswerJSON' => [
                        'options' => ['MySQL', 'Redis', 'Elasticsearch', 'Memcached'],
                        'correct' => [0], // MySQL
                        'multiple' => false,
                    ],
                ]);

                // QR
                PuzzleFactory::createOne([
                    'hunt' => $hunt,
                    'typeAnswer' => 'QR',
                    'question' => 'Scannez le QR code collé sur la porte de la bulle pour valider cette étape.',
                    'contentAnswerJSON' => [
                        'code' => 'QR-IT-00001',
                    ],
                ]);

                // GPS
                PuzzleFactory::createOne([
                    'hunt' => $hunt,
                    'typeAnswer' => 'GPS',
                    'question' => 'Approchez-vous de l\'entrée principale du bâtiment U pour valider cette étape.',
                    'contentAnswerJSON' => [
                        'lat' => 48.8566,
                        'lng' => 2.3522,
                        'radius' => 30,
                    ],
                ]);
            } else {
                $nbPuzzles = random_int(2, 6);
                PuzzleFactory::createMany($nbPuzzles, function () use ($hunt) {
                    return [
                        'hunt' => $hunt,
                    ];
                });
            }
        }

        $manager->flush();
    }
}
