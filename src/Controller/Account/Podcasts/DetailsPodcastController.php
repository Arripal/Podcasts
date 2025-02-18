<?php

namespace App\Controller\Account\Podcasts;

use App\Services\Podcasts\PodcastsService;
use App\Services\Router\RouterService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class DetailsPodcastController extends AbstractController
{
    public function __construct(private RouterService $routerService, private PodcastsService $podcastsService) {}

    #[Route('/app/account/podcasts/details/{identifier}', name: 'app_account_podcasts_details')]
    public function getPodcastDetails($identifier): Response
    {
        $podcastDetails = $this->podcastsService->getOneBy($identifier);

        if ($podcastDetails == null or count($podcastDetails) == 0) {
            $this->addFlash('error', 'Oups. Impossible d\'afficher les informations concernant le podcast. Réessayer ultérieurement.');
            return $this->routerService->generateURL('app_account_podcasts');
        }

        return $this->render('account/podcasts/details_podcast/index.html.twig', [
            'podcast_details' => $podcastDetails,
        ]);
    }
}
