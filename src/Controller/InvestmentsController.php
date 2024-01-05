<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class InvestmentsController extends AbstractController
{
    #[Route('/investments', name: 'app_investments')]
    public function index(): Response
    {
        return $this->render('investments/index.html.twig', [
            'controller_name' => 'InvestmentsController',
        ]);
    }
}
