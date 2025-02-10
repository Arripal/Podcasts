<?php

namespace App\Controller\Account\Podcasts;

use App\Services\Podcasts\PodcastsService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class UpdatePodcastController extends AbstractController
{
    #[Route('/app/account/podcasts/update/{identifier}', name: 'app_account_podcasts_update')]
    public function index($identifier, PodcastsService $podcastsService): Response
    {



        return $this->render('account/podcasts/update_podcast/index.html.twig', [
            'controller_name' => 'Account/Podcasts/UpdatePodcastController',
        ]);
    }
}
