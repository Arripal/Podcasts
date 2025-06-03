<?php

namespace App\Controller\Account\User\Details;

use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class OthersDetailsUserController extends AbstractController
{
    public function __construct(private UserRepository $userRepository, private RequestStack $requestStack) {}

    #[Route('/app/account/user/{username}/details', name: 'app_account_other_user_details')]
    public function index($username): Response
    {
        $decodedUsername = urldecode($username);

        $user = $this->userRepository->findOneBy(['username' => $decodedUsername]);

        if (!$user) {
            $referer = $this->requestStack->getCurrentRequest()->headers->get('referer');
            return $this->redirectToRoute($referer);
        }

        return $this->render('account/user/details_other/index.html.twig', [
            'user' => $user,
        ]);
    }
}
