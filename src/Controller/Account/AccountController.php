<?php

namespace App\Controller\Account;

use App\Services\User\UserService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class AccountController extends AbstractController
{
    #[Route('/app/account', name: 'app_account')]
    public function loadAccount(UserService $userService): Response
    {

        $user = $userService->getAuthenticatedUser();
        $podcasts = $user->getPodcasts();

        return $this->render('account/index.html.twig', [
            'controller_name' => $user->getUsername(),
            'podcasts' => $podcasts
        ]);
    }
}
