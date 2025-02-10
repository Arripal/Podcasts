<?php

namespace App\Controller\Account\Podcasts;

use App\Services\Podcasts\PodcastsService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class PodcastController extends AbstractController
{
    #[Route('/app/account/podcasts', name: 'app_account_podcasts')]
    public function displayPodcasts(Security $security, PodcastsService $podcastsService): Response
    {
        $emptyPodcasts = '';
        $currentUser = $security->getUser();

        $userPodcasts = $podcastsService->getAll($currentUser->getId());

        if ($userPodcasts == null or count($userPodcasts) === 0) {
            $emptyPodcasts = "Vous ne possédez pas de podcast pour le moment.";
        }

        return $this->render('account/podcasts/show/index.html.twig', [
            'empty_podcasts' => $emptyPodcasts,
            'user_podcasts' => $userPodcasts
        ]);
    }
}
