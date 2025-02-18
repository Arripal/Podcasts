<?php

namespace App\Controller\Account\User\Update;

use App\Form\User\UpdatePasswordFormType;
use App\Services\Router\RouterService;
use App\Services\User\UserService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;

class UpdateUserPassword extends AbstractController
{
    public function __construct(private UserService $userService, private RouterService $routerService, private EntityManagerInterface $entityManagerInterface, private TokenStorageInterface $tokenStorageInterface) {}

    #[Route('/app/account/user/update/password', name: 'app_account_user_update_password')]
    public function updatePassword(Request $request, UserPasswordHasherInterface $passwordHasher): Response
    {
        $currentUser = $this->userService->getAuthenticatedUser();
        if (!$currentUser) {
            return $this->routerService->generateURL('app_account_self_user_details');
        }
        $form = $this->createForm(UpdatePasswordFormType::class, $currentUser);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $password = $form->get('password')->getData();
            $hashedPassword = $passwordHasher->hashPassword($currentUser, $password);
            $currentUser->setPassword($hashedPassword);

            $newToken = new UsernamePasswordToken($currentUser, 'main', $currentUser->getRoles());
            $this->tokenStorageInterface->setToken($newToken);

            $this->entityManagerInterface->flush();
            $this->addFlash('success', 'Votre nouveau mot de passe a bien été enregistré.');
            return $this->routerService->generateURL('app_account_self_user_details');
        }

        return $this->render('account/user/update_user/password.html.twig', [
            'form' => $form,
            'current_user' => $currentUser
        ]);
    }
}
