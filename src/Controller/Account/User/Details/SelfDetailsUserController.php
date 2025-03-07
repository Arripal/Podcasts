<?php

namespace App\Controller\Account\User\Details;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

final class SelfDetailsUserController extends AbstractController
{
    #[IsGranted('IS_AUTHENTICATED')]
    #[Route('/app/account/user/details', name: 'app_account_self_user_details')]
    public function getUserDetails(): Response
    {
        return $this->render('account/user/details_user/index.html.twig', [
            'current_user' => $this->getUser(),
        ]);
    }
}
