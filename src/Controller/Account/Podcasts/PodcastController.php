<?php

namespace App\Controller\Account\Podcasts;

use App\Services\Podcasts\PodcastsService;
use App\Services\User\UserService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class PodcastController extends AbstractController
{
    #[Route('/app/account/podcasts', name: 'app_account_podcasts')]
    public function displayPodcasts(UserService $userService, PodcastsService $podcastsService): Response
    {

        $currentUser = $userService->getAuthenticatedUser();

        $userPodcasts = $podcastsService->getAll($currentUser->getId());

        return $this->render('account/podcasts/show/index.html.twig', [
            'user_podcasts' => $userPodcasts
        ]);
    }
}
