<?php

namespace App\Controller\Account;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class DeleteAccountController extends AbstractController
{
    #[Route('/account/delete', name: 'app_account_delete_account')]
    public function deleteAccount(EntityManagerInterface $entityManagerInterface): Response
    {
        $user = $this->getUser();
        $entityManagerInterface->remove($user);
        $this->addFlash('success', 'Votre compte a bien été supprimé.');
        return $this->redirectToRoute('app_login');
    }
}
