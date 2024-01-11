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

    #[Route('/investimentos/json', name: 'app_investments_json')]
    public function investimentos(TokenInterface $token): Response
    {
        $this->denyAccessUnlessGranted('ROLE_USER');

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

        return $this->json($investData);
    }


    #[Route('/investimentos', name: 'app_investments')]
    public function index(TokenInterface $token): Response
    {
        $this->denyAccessUnlessGranted('ROLE_USER');

        return $this->render('investments/investimentos.html.twig', []);
    }


   
    #[Route('/investimentos/{user}/{id}/json', name: 'app_investmentsUser')]
    public function investimentoUser(int $id): Response
    {
        $this->denyAccessUnlessGranted('ROLE_USER');

        $invest = $this->em->getRepository(Invest::class);
        $investimento = $invest->find($id);

        $fusoHorarioSaoPaulo = new \DateTimeZone('America/Sao_Paulo');
        $dataAplicada = $investimento->getDate()->format('Y-m-d');

        $dataInicial = new \DateTime($dataAplicada);
        $dataAtual = new \DateTime('now', $fusoHorarioSaoPaulo);

        $diferenca = $dataInicial->diff($dataAtual);

        $mesesTotais = $diferenca->y * 12 + $diferenca->m;
        
        $value= $investimento->getValue();

        function jurosComposto($value, $meses) {
            $lucro = $value*(1+0.0052)**$meses;
            return $lucro;
        }

        $montanteBig = jurosComposto($value,$mesesTotais);
        $lucroBig = $montanteBig - $value ;
        $lucro = round($lucroBig,2);
        $montante = round($montanteBig,2);


        $investimentoUser[] = [
            'id' => $investimento->getId(),
            'userId' => $investimento->getUser()->getId(),
            'userName' => $investimento->getUser()->getName(),
            'value' => $value,
            'lucroBig' => $montante,
            'lucro' => $lucro,
            'date' => $investimento->getDate()->format('Y-m-d'),

        ];

        return $this->json($investimentoUser);
    }

    #[Route('/investimentos/{user}/{id}', name: 'app_investmentsUserDinamic')]
    public function investimentoUserDinamic(TokenInterface $token, string $user, int $id): Response
    {
        $this->denyAccessUnlessGranted('ROLE_USER');

        if (isset($_POST['submit'])) {
            $userToken = $token->getUser();
            $user = $this->em->getRepository(User::class);
            $userAll = $user->findOneBy(['email' => $userToken->getUserIdentifier()]);

            $invest = $this->em->getRepository(Invest::class);
            $invest = $invest->find($id);
            $userAll->removeInvest($invest);
            $this->em->flush();
            return $this->redirectToRoute('app_home');
        }

        return $this->render('investments/investimentoUser.html.twig', []);
    }
    
}
