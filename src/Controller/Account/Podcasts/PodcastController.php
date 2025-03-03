<?php

namespace App\Controller\Account\Podcasts;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class PodcastController extends AbstractController
{
    #[Route('/app/account/podcasts', name: 'app_account_podcasts')]
    public function showPodcasts(): Response
    {

        $userPodcasts  = $this->getUser()->getPodcasts();

        return $this->render('account/podcasts/show/index.html.twig', [
            'user_podcasts' => $userPodcasts
        ]);
    }
}
