<?php

namespace App\Controller\Home;

use App\Services\User\UserService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class HomeController extends AbstractController
{
    #[Route('/app', name: 'app_home')]
    public function loadLandingPage(UserService $userService): Response
    {
        $user = $userService->getAuthenticatedUser();
        return $this->render('home/index.html.twig', [
            'user' => $user->getUsername(),
        ]);
    }
}
