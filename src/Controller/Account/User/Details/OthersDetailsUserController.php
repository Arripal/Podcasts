<?php

namespace App\Controller\Account\User\Details;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class OthersDetailsUserController extends AbstractController
{
    #[Route('/app/account/user/{identifier}/details', name: 'app_account_other_user_details')]
    public function index(): Response
    {
        return $this->render('account/user/details/others_detailsUser/index.html.twig', [
            'controller_name' => 'Hi',
        ]);
    }
}
