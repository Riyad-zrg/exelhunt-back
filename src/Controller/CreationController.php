<?php

namespace App\Controller;

use Endroid\QrCode\Builder\Builder;
use Endroid\QrCode\Encoding\Encoding;
use Endroid\QrCode\ErrorCorrectionLevel;
use Endroid\QrCode\RoundBlockSizeMode;
use Endroid\QrCode\Writer\PngWriter;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class CreationController extends AbstractController
{
    #[Route('/create', name: 'app_creation')]
    public function index(): Response
    {
        return $this->render('creation/index.html.twig');
    }

    #[Route('/create/team', name: 'app_creation_team')]
    public function team(): Response
    {
        return $this->render('creation/team.html.twig', [
            'controller_name' => 'CreationController',
        ]);
    }

    #[Route('/create/hunt', name: 'app_creation_hunt')]
    public function hunt(): Response
    {
        return $this->render('creation/hunt.html.twig', [
            'controller_name' => 'CreationController',
        ]);
    }

    #[Route('/create/puzzle', name: 'app_creation_puzzle')]
    public function puzzle(): Response
    {
        $randomString = bin2hex(random_bytes(16));

        return $this->render('creation/puzzle/index.html.twig', [
            'randomString' => $randomString,
        ]);
    }

    #[Route('/create/puzzle/txt', name: 'app_creation_txt_puzzle')]
    public function text_puzzle(): Response
    {
        return $this->render('creation/puzzle/txt.html.twig');
    }

    #[Route('/create/puzzle/mcq', name: 'app_creation_mcq_puzzle')]
    public function qcm_puzzle(): Response
    {
        return $this->render('creation/puzzle/mcq.html.twig');
    }

    #[Route('/create/puzzle/qrc', name: 'app_creation_qrc_puzzle')]
    public function qrc_puzzle(): Response
    {
        $randomString = bin2hex(random_bytes(16));

        /* QR code creation using Endroid - usage TBD
        $builder = new Builder(
            writer: new PngWriter(),
            writerOptions: [],
            validateResult: false,
            data: $randomString,
            encoding: new Encoding('UTF-8'),
            errorCorrectionLevel: ErrorCorrectionLevel::High,
            size: 300,
            margin: 10,
            roundBlockSizeMode: RoundBlockSizeMode::Margin,
        );

        $result = $builder->build();
        */
        return $this->render('creation/puzzle/qrc.html.twig', [
            'randomString' => $randomString,
        ]);
    }

    #[Route('/create/puzzle/qrc/download/{randomString}', name: 'app_creation_qrc_dl')]
    public function qrc_download(string $randomString): Response
    {
        $builder = new Builder(
            writer: new PngWriter(),
            writerOptions: [],
            validateResult: false,
            data: $randomString,
            encoding: new Encoding('UTF-8'),
            errorCorrectionLevel: ErrorCorrectionLevel::High,
            size: 300,
            margin: 10,
            roundBlockSizeMode: RoundBlockSizeMode::Margin,
        );

        $result = $builder->build();

        $response = new Response($result->getString());
        $response->headers->set('Content-Type', 'image/png');
        $response->headers->set('Content-Disposition', 'attachment; filename="qrcode.png"');

        return $response;
    }
}
