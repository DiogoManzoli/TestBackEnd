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

        return $this->render('investments/index.html.twig', []);
    }


    #[Route('/investimentos/json', name: 'app_investments_json')]
    public function investimentos(TokenInterface $token): Response
    {
        $this->denyAccessUnlessGranted('ROLE_USER', 'ROLE_ADMIN');

        $userToken = $token->getUser();
        $user = $this->em->getRepository(User::class);
        $userAll = $user->findOneBy(['email' => $userToken->getUserIdentifier()]);

        $investimentos = $userAll->getInvests();

        foreach ($investimentos as $value) {

            $investData[] = [
                'id' => $value->getId(),
                'userId' =>  $value->getUser()->getId(),
                'userName' =>  $value->getUser()->getName(),
                'value' =>  $value->getValue(),
                'date' =>  $value->getDate()->format('Y-m-d'),
            ];
        }

        $json_data = json_encode($investData, JSON_THROW_ON_ERROR);

        return $this->render('json/invest.html.twig', ['json' => $json_data]);
    }
}
