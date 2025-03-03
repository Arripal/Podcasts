<?php

namespace App\Controller\Account;

use App\Services\User\UserService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class DeleteAccountController extends AbstractController
{
    #[Route('/account/delete', name: 'app_account_delete_account')]
    public function deleteAccount(UserService $userService, EntityManagerInterface $entityManagerInterface): Response
    {
        $user = $userService->getAuthenticatedUser();
        $entityManagerInterface->remove($user);
        $this->addFlash('success', 'Votre compte a bien été supprimé.');
        return $this->redirectToRoute('app_login');
    }
}
