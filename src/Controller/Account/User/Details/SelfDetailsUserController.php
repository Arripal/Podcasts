<?php

namespace App\Controller\Account\User\Details;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class SelfDetailsUserController extends AbstractController
{

    #[Route('/app/account/user/details', name: 'app_account_self_user_details')]
    public function getUserDetails(): Response
    {

        $currentUser = $this->getUser();

        if (!$currentUser) {
            $this->addFlash('error', 'Oups. Impossible d\'afficher vos informations. Réessayer ultérieurement.');
            return $this->redirectToRoute('app_account');
        }

        return $this->render('account/user/details_user/index.html.twig', [
            'current_user' => $currentUser,
        ]);
    }
}
