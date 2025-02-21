<?php

namespace App\Controller\Account\User\Subscription;

use App\Services\User\UserService;
use RuntimeException;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class RemoveSubscriptionController extends AbstractController
{

    public function __construct(private UserService $userService) {}

    #[Route('app/account/user/subscription/remove/{username}', name: 'app_account_user_remove_subscription')]
    public function removeSubscription($username): Response
    {
        dd($username);
        try {
            $decodedUsername = urldecode($username);
            $this->userService->removeSubscription($decodedUsername);
            $this->addFlash('success', 'Vous ne suivez plus ' . $decodedUsername);
        } catch (RuntimeException) {
            $this->addFlash('error', 'Impossible de supprimer le podcast.');
        }
        return $this->redirectToRoute('app_account_self_user_details');
    }
}
