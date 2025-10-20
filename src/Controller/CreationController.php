<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class CreationController extends AbstractController
{
    #[Route('/create', name: 'app_creation')]
    public function index(): Response
    {
        return $this->render('creation/index.html.twig', [
            'controller_name' => 'CreationController',
        ]);
    }

    #[Route('/create/text_puzzle', name: 'app_creation_txt_puzzle')]
    public function text_puzzle(): Response
    {
        return $this->render('creation/puzzle/txt.html.twig', [
            'controller_name' => 'CreationController',
        ]);
    }

    #[Route('/create/mcq_puzzle', name: 'app_creation_mcq_puzzle')]
    public function qcm_puzzle(): Response
    {
        return $this->render('creation/puzzle/mcq.html.twig', [
            'controller_name' => 'CreationController',
        ]);
    }

    #[Route('/create/gps_puzzle', name: 'app_creation_gps_puzzle')]
    public function gps_puzzle(): Response
    {
        return $this->render('creation/puzzle/gps.html.twig', [
            'controller_name' => 'CreationController',
        ]);
    }

    #[Route('/create/qrc_puzzle', name: 'app_creation_qrc_puzzle')]
    public function qrc_puzzle(): Response
    {
        return $this->render('creation/puzzle/qrc.html.twig', [
            'controller_name' => 'CreationController',
        ]);
    }

    #[Route('/create/team', name: 'app_creation_team')]
    public function team(): Response
    {
        return $this->render('creation/team.html.twig', [
            'controller_name' => 'CreationController',
        ]);
    }
}
