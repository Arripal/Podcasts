<?php

namespace App\Controller\Account;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class AccountController extends AbstractController
{
    #[Route('/app/account', name: 'app_account')]
    public function index(): Response
    {

        $user = $this->getUser();

        $podcasts = $user->getPodcasts();

        return $this->render('account/index.html.twig', [
            'controller_name' => $user->getUsername(),
            'podcasts' => $podcasts
        ]);
    }
}
