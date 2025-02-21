<?php

namespace App\Controller\Account\User\Subscription;

use App\Services\User\UserService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Exception\BadRequestException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class SubscriptionController extends AbstractController
{

    public function __construct(private UserService $userService, private EntityManagerInterface $entityManagerInterface) {}

    #[Route('/app/account/user/subscribe/{username}', name: 'app_account_user_subscribe', methods: ['POST', 'GET'])]
    public function subscribe($username): Response
    {
        try {
            $decodedUsername = urldecode($username);
            $this->userService->subscription($decodedUsername);
            $this->entityManagerInterface->flush();
            $this->addFlash('success', 'Vous êtes bien abonné à ' . $decodedUsername);
        } catch (BadRequestException $e) {
            $this->addFlash('error', $e->getMessage());
        }
        return $this->redirectToRoute('app_account_self_user_details');
    }
}
