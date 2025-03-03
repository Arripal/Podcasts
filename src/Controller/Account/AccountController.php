<?php

namespace App\Controller\Account;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class AccountController extends AbstractController
{
    #[Route('/app/account', name: 'app_account')]
    public function loadAccount(): Response
    {

        $user = $this->getUser();

        return $this->render('account/index.html.twig', [
            'user' => $user
        ]);
    }
}
