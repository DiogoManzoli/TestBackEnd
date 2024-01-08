<?php

namespace App\Controller;

use App\Entity\Invest;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;

class HomeController extends AbstractController
{
    private $em;
    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    #[Route('/', name: 'app_home')]
    public function index(?TokenInterface $token): Response
    {
        $this->denyAccessUnlessGranted('ROLE_USER', 'ROLE_ADMIN');

        $userToken = $token->getUser();
        $user = $this->em->getRepository(User::class);
        $userAll = $user->findOneBy(['email' => $userToken->getUserIdentifier()]);

        if (isset($_POST['submit']) && !empty($_POST['date']) && !empty($_POST['value'])) {
            
            $dataObj = new \DateTime($_POST['date']);
            $invest = new Invest();
            $invest->setUser($userAll);
            $invest->setDate($dataObj);
            $invest->setValue($_POST['value']);

            $this->em->persist($invest);
            $this->em->flush();

        }

        return $this->render('home/index.html.twig', []);
    }
}
