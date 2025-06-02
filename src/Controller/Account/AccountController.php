<?php

namespace App\Controller\Account;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/app/account', name: 'app_account')]
final class AccountController extends AbstractController
{
    #[Route('/', name: '.get', methods: ['GET'])]
    public function loadAccount(): Response
    {

        $user = $this->getUser();

        return $this->render('account/index.html.twig', [
            'user' => $user
        ]);
    }

    #[Route('/delete', name: '.delete')]
    public function deleteAccount(EntityManagerInterface $entityManagerInterface): Response
    {
        $user = $this->getUser();
        $entityManagerInterface->remove($user);
        $this->addFlash('success', 'Votre compte a bien été supprimé.');
        return $this->redirectToRoute('app_login');
    }
}
