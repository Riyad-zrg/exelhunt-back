<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class CreationController extends AbstractController
{
    #[Route('/creation', name: 'app_creation')]
    public function index(): Response
    {
        return $this->render('creation/index.html.twig', [
            'controller_name' => 'CreationController',
        ]);
    }

    #[Route('/creation/hunt', name: 'app_creation_hunt')]
    public function hunt(): Response
    {
        return $this->render('creation/hunt.html.twig', [
            'controller_name' => 'CreationController',
        ]);
    }

    #[Route('/creation/team', name: 'app_creation_team')]
    public function team(): Response
    {
        return $this->render('creation/team.html.twig', [
            'controller_name' => 'CreationController',
        ]);
    }
}
