<?php

namespace App\Controller\Account\User\Details;

use App\Services\Router\RouterService;
use App\Services\User\UserService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class SelfDetailsUserController extends AbstractController
{

    #[Route('/app/account/user/details', name: 'app_account_self_user_details')]
    public function getUserDetails(UserService $userService, RouterService $routerService): Response
    {

        $currentUser = $userService->getAuthenticatedUser();

        if (!$currentUser) {
            $this->addFlash('error', 'Oups. Impossible d\'afficher vos informations. Réessayer ultérieurement.');
            return $routerService->generateURL('app_account');
        }

        return $this->render('account/user/details_user/index.html.twig', [
            'current_user' => $currentUser,
        ]);
    }
}
