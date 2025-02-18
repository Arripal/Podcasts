<?php

namespace App\Controller\Account\User\Update;

use App\Form\User\UpdateUsernameFormType;
use App\Services\Router\RouterService;
use App\Services\User\UserService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class UpdateUserUsername extends AbstractController
{

    public function __construct(private UserService $userService, private RouterService $routerService, private EntityManagerInterface $entityManagerInterface) {}

    #[Route('/app/account/user/update/username', name: 'app_account_user_update_username')]
    public function updateUsername(Request $request): Response
    {

        $currentUser = $this->userService->getAuthenticatedUser();

        $form = $this->createForm(UpdateUsernameFormType::class, $currentUser);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {

            $username = $form->get('username')->getData();

            $currentUser->setUsername($username);
            $this->entityManagerInterface->flush();
            $this->addFlash('success', 'Votre nouveau pseudonyme a bien été enregistré.');
            return $this->routerService->generateURL('app_account_self_user_details');
        }
        return $this->render('account/user/update_user/username.html.twig', [
            'form' => $form,
            'current_user' => $currentUser
        ]);
    }
}
