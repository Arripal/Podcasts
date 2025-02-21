<?php

namespace App\Controller\Account\User\Details;

use App\Services\User\UserService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class OthersDetailsUserController extends AbstractController
{
    public function __construct(private UserService $userService) {}

    #[Route('/app/account/user/{username}/details', name: 'app_account_other_user_details')]
    public function index($username): Response
    {
        $decodedUsername = urldecode($username);
        $user = $this->userService->findUser(['username' => $decodedUsername]);
        return $this->render('account/user/details_other/index.html.twig', [
            'user' => $user,
        ]);
    }
}
