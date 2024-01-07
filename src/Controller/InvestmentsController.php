<?php

namespace App\Controller;

use App\Entity\Invest;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;

class InvestmentsController extends AbstractController
{
    private $em;
    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    #[Route('/investimentos', name: 'app_investments')]
    public function index(TokenInterface $token): Response
    {
        $this->denyAccessUnlessGranted('ROLE_USER', 'ROLE_ADMIN');

        $userToken = $token->getUser();
        $user = $this->em->getRepository(User::class);
        $userAll = $user->findOneBy(['email' => $userToken->getUserIdentifier()]);
        //$userId = $userAll->getId();

        $invest = $this->em->getRepository(Invest::class);
        $investUser = $invest->find($userAll);
        //dump($investUser);

        return $this->render('investments/index.html.twig', []);
    }
}
