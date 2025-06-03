<?php

namespace App\Controller\Home;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;


final class HomeController extends AbstractController
{

    #[Route('/', name: 'base_url')]
    public function entry(): Response
    {
        return $this->redirectToRoute('app_home');
    }

    #[Route('/app', name: 'app_home')]
    public function loadLandingPage(): Response
    {
        $user = $this->getUser();
        return $this->render('home/index.html.twig', [
            'user' => $user,
        ]);
    }
}
